<?php

namespace App\Http\Controllers;

use App\Models\BillingInfo;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class OrderController_con_policy extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    public function index(Request $request)
    {
        $this->authorize('viewAny', Order::class);

        $orders = $request->user()
            ->orders()
            ->with('items.product', 'billingInfo')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return response()->json(['datos' => $orders, 'message' => 'Historial de pedidos'], Response::HTTP_OK);
    }

    public function show(Request $request, Order $order)
    {
        $this->authorize('view', $order); // Policy: dueño o admin

        return response()->json([
            'datos'   => $order->load('items.product', 'billingInfo'),
            'message' => 'Detalle del pedido'
        ], Response::HTTP_OK);
    }

    public function store(Request $request)
    {
        $this->authorize('create', Order::class);

        $request->validate([
            'items'                => 'required|array|min:1',
            'items.*.product_id'   => 'required|exists:products,id',
            'items.*.quantity'     => 'required|integer|min:1',
            'payment_method'       => 'required|string|in:transferencia,efectivo,qr,deposito',
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
            if ($request->filled('billing_info_id')) {
                $billing = BillingInfo::where('id', $request->billing_info_id)
                    ->where('user_id', $user->id)
                    ->firstOrFail();
            } else {
                $billing = BillingInfo::where('user_id', $user->id)->where('is_default', true)->first();
                if ($request->has('billing')) {
                    $billing = BillingInfo::create(array_merge(
                        ['user_id' => $user->id, 'is_default' => false],
                        $request->billing
                    ));
                }
            }

            $total = 0;
            $orderItemsData = [];

            foreach ($items as $item) {
                $product = $products->get($item['product_id']);
                if (!$product) throw new \Exception("Producto ID {$item['product_id']} no encontrado.");
                if ($product->stock < $item['quantity']) throw new \Exception("Stock insuficiente para: {$product->name}.");

                $price  = $user->getFinalPrice($product->base_price);
                $total += $price * $item['quantity'];

                $orderItemsData[] = [
                    'product_id'         => $product->id,
                    'quantity'           => $item['quantity'],
                    'price_when_ordered' => $price,
                ];

                $product->decrement('stock', $item['quantity']);
            }

            $order = $user->orders()->create([
                'billing_info_id' => $billing?->id,
                'order_number'    => Order::generateOrderNumber(),
                'total'           => $total,
                'status'          => 'pending',
                'payment_method'  => $request->payment_method,
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
            return response()->json(['message' => 'Error: ' . $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    public function cancel(Request $request, Order $order)
    {
        $this->authorize('cancel', $order); // Policy: dueño Y pendiente

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
            return response()->json(['message' => 'Error: ' . $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }
}
