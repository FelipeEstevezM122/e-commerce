<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function __construct()
    {
        // Solo usuarios autenticados pueden crear y ver sus pedidos
        $this->middleware('auth:sanctum');
    }

    /**
     * Historial de pedidos del usuario autenticado.
     */
    public function index(Request $request)
    {
        $orders = $request->user()
            ->orders()
            ->with('items.product') // Cargar items y sus productos
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return response()->json([
            'datos' => $orders,
            'message' => 'Historial de pedidos'
        ], Response::HTTP_OK);
    }

    /**
     * Ver un pedido específico (solo si pertenece al usuario).
     */
    public function show(Request $request, Order $order)
    {
        // Verificar que el pedido pertenezca al usuario autenticado
        if ($order->user_id !== $request->user()->id) {
            return response()->json([
                'message' => 'No tienes permiso para ver este pedido'
            ], Response::HTTP_FORBIDDEN);
        }

        return response()->json([
            'datos' => $order->load('items.product'),
            'message' => 'Detalle del pedido'
        ], Response::HTTP_OK);
    }

    /**
     * Crear un nuevo pedido.
     *
     * Espera un JSON con:
     * {
     *   "items": [
     *     { "product_id": 1, "quantity": 2 },
     *     { "product_id": 3, "quantity": 1 }
     *   ],
     *   "payment_method": "transferencia",
     *   "customer_whatsapp": "59171234567",
     *   "nit": "123456789",
     *   "business_name": "Mi Empresa SRL",
     *   "shipping_address": "Calle 123, La Paz"
     * }
     */
    public function store(Request $request)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'payment_method' => 'required|string|in:transferencia,efectivo,tarjeta',
            'customer_whatsapp' => 'nullable|string|max:20',
            'nit' => 'nullable|string|max:50',
            'business_name' => 'nullable|string|max:255',
            'shipping_address' => 'nullable|string|max:500',
        ]);

        $user = $request->user();
        $items = $request->items;

        // Obtener todos los productos solicitados
        $productIds = collect($items)->pluck('product_id');
        $products = Product::whereIn('id', $productIds)->get()->keyBy('id');

        $total = 0;
        $orderItemsData = [];

        DB::beginTransaction();
        try {
            // Validar stock disponible y calcular total
            foreach ($items as $item) {
                $product = $products->get($item['product_id']);
                if (!$product) {
                    throw new \Exception("Producto con ID {$item['product_id']} no encontrado.");
                }
                if ($product->stock < $item['quantity']) {
                    throw new \Exception("Stock insuficiente para el producto: {$product->name}. Disponible: {$product->stock}");
                }

                // Aplicar descuento si el usuario es mayorista
                $price = $user->getFinalPrice($product->price);
                $subtotal = $price * $item['quantity'];
                $total += $subtotal;

                $orderItemsData[] = [
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'price_when_ordered' => $price, // precio con posible descuento
                ];

                // Reducir stock
                $product->decrement('stock', $item['quantity']);
            }

            // Crear el pedido con todos los campos
            $order = $user->orders()->create([
                'order_number' => Order::generateOrderNumber(),
                'total' => $total,
                'status' => 'pending',
                'payment_method' => $request->payment_method,
                'customer_whatsapp' => $request->customer_whatsapp ?? $user->whatsapp,
                'nit' => $request->nit,
                'business_name' => $request->business_name,
                'shipping_address' => $request->shipping_address ?? $user->address,
            ]);

            // Crear los items del pedido usando OrderItem
            foreach ($orderItemsData as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price_when_ordered' => $item['price_when_ordered'],
                ]);
            }

            DB::commit();

            return response()->json([
                'datos' => $order->load('items.product'),
                'message' => 'Pedido creado exitosamente'
            ], Response::HTTP_CREATED);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Error al crear el pedido: ' . $e->getMessage()
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Cancelar un pedido (solo si está pendiente)
     */
    public function cancel(Request $request, Order $order)
    {
        // Verificar que el pedido pertenezca al usuario
        if ($order->user_id !== $request->user()->id) {
            return response()->json([
                'message' => 'No tienes permiso para cancelar este pedido'
            ], Response::HTTP_FORBIDDEN);
        }

        // Solo se puede cancelar si está pendiente
        if (!$order->isPending()) {
            return response()->json([
                'message' => 'Solo se pueden cancelar pedidos pendientes'
            ], Response::HTTP_BAD_REQUEST);
        }

        DB::beginTransaction();
        try {
            // Restaurar el stock de los productos
            foreach ($order->items as $item) {
                $product = Product::find($item->product_id);
                if ($product) {
                    $product->increment('stock', $item->quantity);
                }
            }

            // Cambiar estado a cancelado
            $order->changeStatus('cancelled');

            DB::commit();

            return response()->json([
                'message' => 'Pedido cancelado exitosamente'
            ], Response::HTTP_OK);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Error al cancelar el pedido: ' . $e->getMessage()
            ], Response::HTTP_BAD_REQUEST);
        }
    }
}