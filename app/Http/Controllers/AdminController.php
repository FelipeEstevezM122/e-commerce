<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use App\Models\Product;
use App\Models\Order;
use App\Models\BillingInfo;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    public function dashboard()
    {
        $totalUsers = User::count();
        $totalProducts = Product::count();
        $totalOrders = Order::count();
        $pendingOrders = Order::where('status', 'pending')->count();
        $completedOrders = Order::where('status', 'delivered')->count();
        $totalSales = Order::where('status', 'delivered')->sum('total');

        $wholesalers = User::whereHas('roles', fn($q) => $q->where('name', 'mayorista'))->count();
        $finalCustomers = User::whereHas('roles', fn($q) => $q->where('name', 'cliente'))->count();

        $topProducts = DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->select('products.id', 'products.name', DB::raw('SUM(order_items.quantity) as total_sold'))
            ->groupBy('products.id', 'products.name')
            ->orderBy('total_sold', 'desc')
            ->limit(5)
            ->get();

        $salesByMonth = Order::where('status', 'delivered')
            ->where('created_at', '>=', now()->subMonths(6))
            ->select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('YEAR(created_at) as year'),
                DB::raw('SUM(total) as total')
            )
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get();

        if (request()->wantsJson()) {
            return response()->json([
                'datos' => [
                    'users' => ['total' => $totalUsers, 'wholesalers' => $wholesalers, 'final_customers' => $finalCustomers],
                    'products' => ['total' => $totalProducts],
                    'orders' => ['total' => $totalOrders, 'pending' => $pendingOrders, 'completed' => $completedOrders],
                    'sales' => ['total' => $totalSales, 'by_month' => $salesByMonth],
                    'top_products' => $topProducts,
                ],
                'message' => 'Estadísticas del sistema'
            ], Response::HTTP_OK);
        }

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalProducts',
            'totalOrders',
            'pendingOrders',
            'completedOrders',
            'totalSales',
            'wholesalers',
            'finalCustomers',
            'topProducts',
            'salesByMonth'
        ));
    }

    // Crear administrador desde el dashboard Blade

    public function storeAdmin(Request $request)
    {
        $request->validate([
            'admin_name' => 'required|string|max:100',
            'admin_email' => 'required|string|email|max:100|unique:users,email',
            'admin_password' => 'required|string|min:8|confirmed',
        ]);

        DB::beginTransaction();
        try {
            $user = User::create([
                'name' => $request->admin_name,
                'email' => $request->admin_email,
                'password' => Hash::make($request->admin_password),
            ]);

            $adminRole = Role::where('name', 'admin')->first();
            if ($adminRole) {
                $user->roles()->attach($adminRole, ['assigned_at' => now()]);
            }

            DB::commit();

            return redirect()->route('admin.dashboard')
                ->with('admin_created', "Administrador '{$user->name}' creado correctamente.");

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.dashboard')
                ->with('admin_error', 'Error al crear el administrador: ' . $e->getMessage());
        }
    }

    // ─────────────────────────────────────────────────────────────────────────

    public function users(Request $request)
    {
        $query = User::with('rank', 'roles');

        if ($request->filled('user_type')) {
            $query->whereHas('roles', fn($q) => $q->where('name', $request->user_type));
        }
        if ($request->filled('role')) {
            $query->whereHas('roles', fn($q) => $q->where('name', $request->role));
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(
                fn($q) => $q
                    ->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('email', 'LIKE', "%{$search}%")
                    ->orWhere('phone', 'LIKE', "%{$search}%")
            );
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(15);

        if ($request->wantsJson()) {
            return response()->json(['datos' => $users, 'message' => 'Lista de usuarios'], Response::HTTP_OK);
        }

        return view('admin.users', compact('users'));
    }

    public function showUser(User $user)
    {
        $user->load('rank', 'roles', 'orders', 'accumulatedPurchases', 'billingInfo');

        $totalOrders = $user->orders()->count();
        $totalSpent = $user->orders()->where('status', 'delivered')->sum('total');
        $averageOrder = $totalOrders > 0 ? $totalSpent / $totalOrders : 0;

        if (request()->wantsJson()) {
            return response()->json([
                'datos' => ['user' => $user, 'statistics' => compact('totalOrders', 'totalSpent', 'averageOrder')],
                'message' => 'Detalles del usuario'
            ], Response::HTTP_OK);
        }

        return view('admin.users.show', compact('user', 'totalOrders', 'totalSpent', 'averageOrder'));
    }

    public function createUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'phone' => 'nullable|string|max:20',
            'whatsapp' => 'nullable|string|max:20',
            'access_code' => 'nullable|string|size:6',
            'rank_id' => 'nullable|exists:ranks,id',
            'roles' => 'nullable|array',
            'roles.*' => 'exists:roles,id',
            'billing.address' => 'nullable|string',
            'billing.company_name' => 'nullable|string|max:150',
            'billing.nit' => 'nullable|string|max:20',
            'billing.business_name' => 'nullable|string|max:150',
            'billing.whatsapp' => 'nullable|string|max:20',
        ]);

        DB::beginTransaction();
        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'phone' => $request->phone,
                'whatsapp' => $request->whatsapp,
                'access_code' => $request->access_code,
                'rank_id' => $request->rank_id,
            ]);

            if ($request->has('roles')) {
                $user->roles()->attach($request->roles, ['assigned_at' => now()]);
            }

            if ($request->has('billing')) {
                BillingInfo::create(array_merge(
                    ['user_id' => $user->id, 'is_default' => true],
                    $request->billing
                ));
            }

            DB::commit();

            if ($request->wantsJson()) {
                return response()->json([
                    'datos' => $user->load('roles', 'rank', 'billingInfo'),
                    'message' => 'Usuario creado exitosamente'
                ], Response::HTTP_CREATED);
            }

            return redirect()->route('admin.users.index')->with('success', 'Usuario creado exitosamente');

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Error: ' . $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    public function updateUser(Request $request, User $user)
    {
        $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8',
            'phone' => 'nullable|string|max:20',
            'whatsapp' => 'nullable|string|max:20',
            'access_code' => 'nullable|string|size:6',
            'rank_id' => 'nullable|exists:ranks,id',
            'roles' => 'nullable|array',
            'roles.*' => 'exists:roles,id',
            'billing.address' => 'nullable|string',
            'billing.company_name' => 'nullable|string|max:150',
            'billing.nit' => 'nullable|string|max:20',
            'billing.business_name' => 'nullable|string|max:150',
            'billing.whatsapp' => 'nullable|string|max:20',
        ]);

        DB::beginTransaction();
        try {
            $data = $request->only(['name', 'email', 'phone', 'whatsapp', 'access_code', 'rank_id']);

            if ($request->filled('password')) {
                $data['password'] = Hash::make($request->password);
            }

            $user->update($data);

            if ($request->has('roles')) {
                $user->roles()->syncWithPivotValues($request->roles, ['assigned_at' => now()]);
            }

            if ($request->has('billing')) {
                $billing = BillingInfo::where('user_id', $user->id)->where('is_default', true)->first();
                if ($billing) {
                    $billing->update($request->billing);
                } else {
                    BillingInfo::create(array_merge(['user_id' => $user->id, 'is_default' => true], $request->billing));
                }
            }

            DB::commit();

            if ($request->wantsJson()) {
                return response()->json([
                    'datos' => $user->load('roles', 'rank', 'billingInfo'),
                    'message' => 'Usuario actualizado exitosamente'
                ], Response::HTTP_OK);
            }

            return redirect()->route('admin.users.index')->with('success', 'Usuario actualizado exitosamente');

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Error: ' . $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    public function deleteUser(Request $request, User $user)
    {
        $force = $request->boolean('force', false);

        if ($force && in_array(\Illuminate\Database\Eloquent\SoftDeletes::class, class_uses_recursive($user))) {
            $user->forceDelete();
            $message = 'Usuario eliminado permanentemente';
        } else {
            $user->delete();
            $message = 'Usuario eliminado';
        }

        if ($request->wantsJson()) {
            return response()->json(['message' => $message], Response::HTTP_OK);
        }

        return redirect()->route('admin.users.index')->with('success', $message);
    }

    public function products(Request $request)
    {
        $query = Product::with('brand', 'category');

        if ($request->has('low_stock')) {
            $query->where('stock', '<=', $request->input('threshold', 10));
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(
                fn($q) => $q
                    ->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('description', 'LIKE', "%{$search}%")
            );
        }

        $products = $query->orderBy('created_at', 'desc')->paginate(15);

        if ($request->wantsJson()) {
            return response()->json(['datos' => $products, 'message' => 'Lista de productos'], Response::HTTP_OK);
        }

        return view('admin.products.index', compact('products'));
    }

    public function orders(Request $request)
    {
        $query = Order::with('user', 'items.product', 'billingInfo');

        if ($request->filled('status'))
            $query->where('status', $request->status);
        if ($request->filled('from_date'))
            $query->whereDate('created_at', '>=', $request->from_date);
        if ($request->filled('to_date'))
            $query->whereDate('created_at', '<=', $request->to_date);
        if ($request->filled('user_id'))
            $query->where('user_id', $request->user_id);

        $orders = $query->orderBy('created_at', 'desc')->paginate(15);

        $pendingCount = Order::where('status', 'pending')->count();
        $shippedCount = Order::where('status', 'shipped')->count();
        $deliveredCount = Order::where('status', 'delivered')->count();

        if ($request->wantsJson()) {
            return response()->json(['datos' => $orders, 'message' => 'Lista de pedidos'], Response::HTTP_OK);
        }

        return view('admin.orders', compact(
            'orders',
            'pendingCount',
            'shippedCount',
            'deliveredCount'
        ));
    }

    public function updateOrderStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,paid,shipped,delivered,cancelled'
        ]);

        if (in_array($order->status, ['delivered', 'cancelled'])) {
            if ($request->wantsJson()) {
                return response()->json(['message' => 'Este pedido ya no puede ser modificado.'], Response::HTTP_UNPROCESSABLE_ENTITY);
            }
            return redirect()->back()->with('error', 'Este pedido ya no puede ser modificado.');
        }

        $order->update(['status' => $request->status]);

        if ($request->wantsJson()) {
            return response()->json(['datos' => $order, 'message' => 'Estado actualizado'], Response::HTTP_OK);
        }

        return redirect()->back()->with('success', 'Estado del pedido actualizado');
    }

    public function salesReport(Request $request)
    {
        $request->validate(['period' => 'nullable|in:day,week,month,year']);
        $period = $request->input('period', 'month');

        [$groupBy, $dateFormat, $startDate] = match ($period) {
            'day' => ['HOUR', '%H:00', now()->startOfDay()],
            'week' => ['DAY', '%W', now()->startOfWeek()],
            'year' => ['MONTH', '%M', now()->startOfYear()],
            default => ['DAY', '%d', now()->startOfMonth()],
        };

        $sales = Order::where('status', 'delivered')
            ->where('created_at', '>=', $startDate)
            ->select(
                DB::raw("DATE_FORMAT(created_at, '{$dateFormat}') as period_label"),
                DB::raw("{$groupBy}(created_at) as period"),
                DB::raw('COUNT(*) as total_orders'),
                DB::raw('SUM(total) as total_sales')
            )
            ->groupBy('period', 'period_label')
            ->orderBy('period')
            ->get();

        return response()->json([
            'datos' => [
                'period' => $period,
                'data' => $sales,
                'summary' => ['total_orders' => $sales->sum('total_orders'), 'total_sales' => $sales->sum('total_sales')],
            ],
            'message' => 'Reporte de ventas'
        ], Response::HTTP_OK);
    }

    public function salesReportProcedure(Request $request)
    {
        $request->validate(['start_date' => 'required|date', 'end_date' => 'required|date|after_or_equal:start_date', 'status' => 'nullable|string']);
        $results = DB::select('CALL sp_sales_report(?, ?, ?)', [$request->start_date, $request->end_date, $request->status]);
        return response()->json(['datos' => $results, 'message' => 'Reporte de ventas generado'], Response::HTTP_OK);
    }

    public function topProductsProcedure(Request $request)
    {
        $request->validate(['limit' => 'nullable|integer|min:1|max:100', 'start_date' => 'nullable|date', 'end_date' => 'nullable|date']);
        $results = DB::select('CALL sp_top_products(?, ?, ?)', [$request->input('limit', 10), $request->start_date, $request->end_date]);
        return response()->json(['datos' => $results, 'message' => 'Top productos'], Response::HTTP_OK);
    }

    public function customerStatisticsProcedure(Request $request)
    {
        $request->validate(['min_orders' => 'nullable|integer|min:0', 'min_spent' => 'nullable|numeric|min:0']);
        $results = DB::select('CALL sp_customer_statistics(?, ?)', [$request->input('min_orders', 0), $request->input('min_spent', 0)]);
        return response()->json(['datos' => $results, 'message' => 'Estadísticas de clientes'], Response::HTTP_OK);
    }

    public function inventoryAlertsProcedure(Request $request)
    {
        $request->validate(['threshold' => 'nullable|integer|min:0']);
        $results = DB::select('CALL sp_inventory_management(?)', [$request->input('threshold', 10)]);
        return response()->json(['datos' => $results, 'message' => 'Alertas de inventario'], Response::HTTP_OK);
    }

    public function executiveDashboardProcedure(Request $request)
    {
        $request->validate(['days' => 'nullable|integer|min:1|max:365']);
        $results = DB::select('CALL sp_executive_dashboard(?)', [$request->input('days', 30)]);
        return response()->json([
            'datos' => ['summary' => $results[0] ?? null, 'top_products' => array_slice($results, 1, 5)],
            'message' => 'Dashboard ejecutivo'
        ], Response::HTTP_OK);
    }
}