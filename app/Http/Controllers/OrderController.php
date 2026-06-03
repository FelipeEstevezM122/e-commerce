<?php

namespace App\Http\Controllers;

use App\Models\BillingInfo;
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
        $this->middleware('auth:sanctum');
    }

    public function index(Request $request)
    {
        $orders = $request->user()
            ->orders()
            ->with('items.product', 'billingInfo') // FIX: agrega billingInfo
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return response()->json(['datos' => $orders, 'message' => 'Historial de pedidos'], Response::HTTP_OK);
    }

    public function show(Request $request, Order $order)
    {
        if ($order->user_id !== $request->user()->id) {
            return response()->json(['message' => 'No tienes permiso para ver este pedido'], Response::HTTP_FORBIDDEN);
        }

        return response()->json([
            'datos'   => $order->load('items.product', 'billingInfo'), // FIX: agrega billingInfo
            'message' => 'Detalle del pedido'
        ], Response::HTTP_OK);
    }

    /**
     * Crear un nuevo pedido.
     *
     * Espera JSON:
     * {
     *   "items": [
     *     { "product_id": 1, "quantity": 2 }
     *   ],
     *   "payment_method": "transferencia",
     *   "billing_info_id": 3          ← usar uno existente, O enviar datos nuevos:
     *   "billing": {                  ← crear uno nuevo al momento
     *     "address": "Calle 123",
     *     "nit": "123456",
     *     "business_name": "Mi SRL",
     *     "whatsapp": "59171234567"
     *   }
     * }
     */
    public function store(Request $request)
    {
        $request->validate([
            'items'                => 'required|array|min:1',
            'items.*.product_id'   => 'required|exists:products,id',
            'items.*.quantity'     => 'required|integer|min:1',
            'payment_method'       => 'required|string|in:transferencia,efectivo,qr,deposito',
            // FIX: billing_info_id o datos nuevos de billing
            'billing_info_id'      => 'nullable|exists:billing_info,id',
            'billing.address'      => 'nullable|string|max:500',
            'billing.nit'          => 'nullable|string|max:20',
            'billing.business_name'=> 'nullable|string|max:150',
            'billing.company_name' => 'nullable|string|max:150',
            'billing.whatsapp'     => 'nullable|string|max:20',
        ]);

        $user     = $request->user();
        $items    = $request->items;
        $products = Product::whereIn('id', collect($items)->pluck('product_id'))->get()->keyBy('id');

        DB::beginTransaction();
        try {
            // Resolver billing_info
            if ($request->filled('billing_info_id')) {
                $billing = BillingInfo::where('id', $request->billing_info_id)
                    ->where('user_id', $user->id) // seguridad: solo las propias
                    ->firstOrFail();
            } else {
                // Crear o usar el default
                $billing = BillingInfo::where('user_id', $user->id)->where('is_default', true)->first();

                if ($request->has('billing')) {
                    $billing = BillingInfo::create(array_merge(
                        ['user_id' => $user->id, 'is_default' => false],
                        $request->billing
                    ));
                }
            }

            $total          = 0;
            $orderItemsData = [];

            foreach ($items as $item) {
                $product = $products->get($item['product_id']);

                if (!$product) throw new \Exception("Producto ID {$item['product_id']} no encontrado.");
                if ($product->stock < $item['quantity']) throw new \Exception("Stock insuficiente para: {$product->name}. Disponible: {$product->stock}");

                // FIX: precio correcto es base_price (no price)
                $price    = $user->getFinalPrice($product->base_price);
                $total   += $price * $item['quantity'];

                $orderItemsData[] = [
                    'product_id'         => $product->id,
                    'quantity'           => $item['quantity'],
                    'price_when_ordered' => $price,
                ];

                $product->decrement('stock', $item['quantity']);
            }

            $order = $user->orders()->create([
                'billing_info_id' => $billing?->id, // FIX: FK a billing_info
                'order_number'    => Order::generateOrderNumber(),
                'total'           => $total,
                'status'          => 'pending',
                'payment_method'  => $request->payment_method,
                // FIX: eliminados customer_whatsapp, nit, business_name, shipping_address
            ]);

            foreach ($orderItemsData as $item) {
                OrderItem::create(array_merge(['order_id' => $order->id], $item));
            }

            DB::commit();

            return response()->json([
                'datos'   => $order->load('items.product', 'billingInfo'),
                'message' => 'Pedido creado exitosamente'
            ], Response::HTTP_CREATED);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Error al crear el pedido: ' . $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    public function cancel(Request $request, Order $order)
    {
        if ($order->user_id !== $request->user()->id) {
            return response()->json(['message' => 'No tienes permiso para cancelar este pedido'], Response::HTTP_FORBIDDEN);
        }

        if (!$order->isPending()) {
            return response()->json(['message' => 'Solo se pueden cancelar pedidos pendientes'], Response::HTTP_BAD_REQUEST);
        }

        DB::beginTransaction();
        try {
            foreach ($order->items as $item) {
                Product::find($item->product_id)?->increment('stock', $item->quantity);
            }
            $order->changeStatus('cancelled');
            DB::commit();

            return response()->json(['message' => 'Pedido cancelado exitosamente'], Response::HTTP_OK);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Error al cancelar: ' . $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }
}
