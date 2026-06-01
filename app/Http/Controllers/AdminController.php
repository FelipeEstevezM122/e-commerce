<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use App\Models\Rank;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function __construct()
    {
        // Solo usuarios autenticados pueden acceder
        $this->middleware('auth:sanctum');
        // Verificar que sea administrador
        $this->middleware('admin');
    }

    /**
     * Dashboard con estadísticas generales
     */
    public function dashboard()
    {
        $totalUsers = User::count();
        $totalProducts = Product::count();
        $totalOrders = Order::count();
        $pendingOrders = Order::where('status', 'pending')->count();
        $completedOrders = Order::where('status', 'completed')->count();
        
        // Ventas totales
        $totalSales = Order::where('status', 'completed')->sum('total');
        
        // Usuarios por tipo
        $wholesalers = User::where('user_type', 'mayorista')->count();
        $finalCustomers = User::where('user_type', 'final')->count();
        
        // Top 5 productos más vendidos
        $topProducts = DB::table('order_product')
            ->join('products', 'order_product.product_id', '=', 'products.id')
            ->select('products.id', 'products.name', DB::raw('SUM(order_product.quantity) as total_sold'))
            ->groupBy('products.id', 'products.name')
            ->orderBy('total_sold', 'desc')
            ->limit(5)
            ->get();
        
        // Ventas por mes (últimos 6 meses)
        $salesByMonth = Order::where('status', 'completed')
            ->where('created_at', '>=', now()->subMonths(6))
            ->select(DB::raw('MONTH(created_at) as month'), DB::raw('YEAR(created_at) as year'), DB::raw('SUM(total) as total'))
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get();
        
        return response()->json([
            'datos' => [
                'users' => [
                    'total' => $totalUsers,
                    'wholesalers' => $wholesalers,
                    'final_customers' => $finalCustomers,
                ],
                'products' => [
                    'total' => $totalProducts,
                ],
                'orders' => [
                    'total' => $totalOrders,
                    'pending' => $pendingOrders,
                    'completed' => $completedOrders,
                ],
                'sales' => [
                    'total' => $totalSales,
                    'by_month' => $salesByMonth,
                ],
                'top_products' => $topProducts,
            ],
            'message' => 'Estadísticas del sistema'
        ], Response::HTTP_OK);
    }

    /**
     * Listar todos los usuarios con filtros opcionales
     */
    public function users(Request $request)
    {
        $query = User::with('rank', 'roles');
        
        // Filtro por tipo de usuario
        if ($request->has('user_type') && $request->user_type) {
            $query->where('user_type', $request->user_type);
        }
        
        // Filtro por rol
        if ($request->has('role') && $request->role) {
            $query->whereHas('roles', function ($q) use ($request) {
                $q->where('name', $request->role);
            });
        }
        
        // Búsqueda por nombre o email
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%")
                  ->orWhere('phone', 'LIKE', "%{$search}%");
            });
        }
        
        $users = $query->orderBy('created_at', 'desc')->paginate(15);
        
        return response()->json([
            'datos' => $users,
            'message' => 'Lista de usuarios'
        ], Response::HTTP_OK);
    }

    /**
     * Ver detalles de un usuario específico
     */
    public function showUser(User $user)
    {
        $user->load('rank', 'roles', 'orders', 'accumulatedPurchases');
        
        // Estadísticas del usuario
        $totalOrders = $user->orders()->count();
        $totalSpent = $user->orders()->where('status', 'completed')->sum('total');
        $averageOrder = $totalOrders > 0 ? $totalSpent / $totalOrders : 0;
        
        return response()->json([
            'datos' => [
                'user' => $user,
                'statistics' => [
                    'total_orders' => $totalOrders,
                    'total_spent' => $totalSpent,
                    'average_order' => $averageOrder,
                ]
            ],
            'message' => 'Detalles del usuario'
        ], Response::HTTP_OK);
    }

    /**
     * Crear un nuevo usuario (admin)
     */
    public function createUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'phone' => 'nullable|string|max:20',
            'whatsapp' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'company_name' => 'nullable|string|max:255',
            'user_type' => 'required|in:final,mayorista',
            'access_code' => 'nullable|string|size:6',
            'rank_id' => 'nullable|exists:ranks,id',
            'roles' => 'nullable|array',
            'roles.*' => 'exists:roles,id'
        ]);
        
        DB::beginTransaction();
        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'phone' => $request->phone,
                'whatsapp' => $request->whatsapp,
                'address' => $request->address,
                'company_name' => $request->company_name,
                'user_type' => $request->user_type,
                'access_code' => $request->access_code,
                'rank_id' => $request->rank_id,
            ]);
            
            // Asignar roles si se proporcionaron
            if ($request->has('roles')) {
                $user->roles()->attach($request->roles, ['assigned_at' => now()]);
            }
            
            DB::commit();
            
            return response()->json([
                'datos' => $user->load('roles', 'rank'),
                'message' => 'Usuario creado exitosamente'
            ], Response::HTTP_CREATED);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Error al crear usuario: ' . $e->getMessage()
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Actualizar un usuario
     */
    public function updateUser(Request $request, User $user)
    {
        $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8',
            'phone' => 'nullable|string|max:20',
            'whatsapp' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'company_name' => 'nullable|string|max:255',
            'user_type' => 'sometimes|in:final,mayorista',
            'access_code' => 'nullable|string|size:6',
            'rank_id' => 'nullable|exists:ranks,id',
            'roles' => 'nullable|array',
            'roles.*' => 'exists:roles,id'
        ]);
        
        DB::beginTransaction();
        try {
            $data = $request->except(['password', 'roles']);
            
            if ($request->has('password') && $request->password) {
                $data['password'] = Hash::make($request->password);
            }
            
            $user->update($data);
            
            // Actualizar roles si se proporcionaron
            if ($request->has('roles')) {
                $user->roles()->syncWithPivotValues($request->roles, ['assigned_at' => now()]);
            }
            
            DB::commit();
            
            return response()->json([
                'datos' => $user->load('roles', 'rank'),
                'message' => 'Usuario actualizado exitosamente'
            ], Response::HTTP_OK);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Error al actualizar usuario: ' . $e->getMessage()
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Eliminar un usuario (soft delete o hard delete)
     */
    public function deleteUser(User $user, Request $request)
    {
        $force = $request->input('force', false);
        
        if ($force) {
            $user->forceDelete();
            $message = 'Usuario eliminado permanentemente';
        } else {
            $user->delete();
            $message = 'Usuario eliminado (puede restaurarse)';
        }
        
        return response()->json([
            'message' => $message
        ], Response::HTTP_OK);
    }

    /**
     * Listar todos los productos (admin view)
     */
    public function products(Request $request)
    {
        $query = Product::with('user');
        
        // Filtro por stock bajo
        if ($request->has('low_stock')) {
            $threshold = $request->input('threshold', 10);
            $query->where('stock', '<=', $threshold);
        }
        
        // Búsqueda
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%");
            });
        }
        
        $products = $query->orderBy('created_at', 'desc')->paginate(15);
        
        return response()->json([
            'datos' => $products,
            'message' => 'Lista de productos'
        ], Response::HTTP_OK);
    }

    /**
     * Listar todos los pedidos (admin view)
     */
    public function orders(Request $request)
    {
        $query = Order::with('user', 'products');
        
        // Filtro por estado
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }
        
        // Filtro por rango de fechas
        if ($request->has('from_date') && $request->from_date) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }
        
        if ($request->has('to_date') && $request->to_date) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }
        
        // Filtro por usuario
        if ($request->has('user_id') && $request->user_id) {
            $query->where('user_id', $request->user_id);
        }
        
        $orders = $query->orderBy('created_at', 'desc')->paginate(15);
        
        return response()->json([
            'datos' => $orders,
            'message' => 'Lista de pedidos'
        ], Response::HTTP_OK);
    }

    /**
     * Actualizar estado de un pedido
     */
    public function updateOrderStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,shipped,completed,cancelled'
        ]);
        
        $order->update(['status' => $request->status]);
        
        return response()->json([
            'datos' => $order,
            'message' => 'Estado del pedido actualizado'
        ], Response::HTTP_OK);
    }

    /**
     * Estadísticas de ventas
     */
    public function salesReport(Request $request)
    {
        $request->validate([
            'period' => 'nullable|in:day,week,month,year'
        ]);
        
        $period = $request->input('period', 'month');
        
        switch ($period) {
            case 'day':
                $groupBy = 'HOUR';
                $dateFormat = '%H:00';
                $startDate = now()->startOfDay();
                break;
            case 'week':
                $groupBy = 'DAY';
                $dateFormat = '%W';
                $startDate = now()->startOfWeek();
                break;
            case 'month':
                $groupBy = 'DAY';
                $dateFormat = '%d';
                $startDate = now()->startOfMonth();
                break;
            case 'year':
                $groupBy = 'MONTH';
                $dateFormat = '%M';
                $startDate = now()->startOfYear();
                break;
            default:
                $groupBy = 'DAY';
                $dateFormat = '%Y-%m-%d';
                $startDate = now()->subDays(30);
        }
        
        $sales = Order::where('status', 'completed')
            ->where('created_at', '>=', $startDate)
            ->select(
                DB::raw("DATE_FORMAT(created_at, '{$dateFormat}') as period_label"),
                DB::raw("{$groupBy}(created_at) as period"),
                DB::raw('COUNT(*) as total_orders'),
                DB::raw('SUM(total) as total_sales')
            )
            ->groupBy('period', 'period_label')
            ->orderBy('period', 'asc')
            ->get();
        
        return response()->json([
            'datos' => [
                'period' => $period,
                'data' => $sales,
                'summary' => [
                    'total_orders' => $sales->sum('total_orders'),
                    'total_sales' => $sales->sum('total_sales'),
                ]
            ],
            'message' => 'Reporte de ventas'
        ], Response::HTTP_OK);
    }
}