<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CartController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    public function index(Request $request)
    {
        $cart = $this->getOrCreateCart($request->user());
        $cart->load('items.product');

        return response()->json([
            'datos' => [
                'cart_id'     => $cart->id,
                'items'       => $cart->items,
                'total_items' => $cart->total_items,
                'total_price' => $cart->total,
                'is_empty'    => $cart->isEmpty(),
            ],
            'message' => 'Carrito actual'
        ], Response::HTTP_OK);
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity'   => 'required|integer|min:1',
        ]);

        $user      = $request->user();
        $productId = (int) $request->product_id;
        $quantity  = (int) $request->quantity;
        $product   = Product::findOrFail($productId);

        if ($product->stock < $quantity) {
            return response()->json(['message' => "Stock insuficiente. Disponible: {$product->stock}"], Response::HTTP_BAD_REQUEST);
        }

        $cart     = $this->getOrCreateCart($user);
        $cartItem = CartItem::where('cart_id', $cart->id)->where('product_id', $productId)->first();

        if ($cartItem) {
            $newQty = $cartItem->quantity + $quantity;
            if ($product->stock < $newQty) {
                $available = $product->stock - $cartItem->quantity;
                return response()->json(['message' => "Solo puedes agregar {$available} más (ya tienes {$cartItem->quantity} en el carrito)"], Response::HTTP_BAD_REQUEST);
            }
            $cartItem->update(['quantity' => $newQty]);
        } else {
            CartItem::create([
                'cart_id'          => $cart->id,
                'product_id'       => $productId,
                'quantity'         => $quantity,
                'price_when_added' => $product->base_price, // FIX: era $product->price → es base_price
            ]);
        }

        $cart->load('items.product');

        return response()->json([
            'datos' => [
                'cart' => [
                    'cart_id'     => $cart->id,
                    'items'       => $cart->items,
                    'total_items' => $cart->total_items,
                    'total_price' => $cart->total,
                ],
                'added_product' => [
                    'product_id'       => $product->id,
                    'name'             => $product->name,
                    'quantity'         => $quantity,
                    'price_when_added' => $product->base_price, // FIX: base_price
                ],
            ],
            'message' => 'Producto agregado al carrito'
        ], Response::HTTP_CREATED);
    }

    public function update(Request $request, int $itemId)
    {
        $request->validate(['quantity' => 'required|integer|min:1']);

        $cart     = $this->getOrCreateCart($request->user());
        $cartItem = CartItem::where('cart_id', $cart->id)->where('id', $itemId)->firstOrFail();
        $product  = Product::findOrFail($cartItem->product_id);

        if ($product->stock < $request->quantity) {
            return response()->json(['message' => "Stock insuficiente. Disponible: {$product->stock}"], Response::HTTP_BAD_REQUEST);
        }

        $cartItem->update(['quantity' => (int) $request->quantity]);
        $cart->load('items.product');

        return response()->json([
            'datos'   => ['cart_item' => $cartItem->load('product'), 'item_total' => $cartItem->total, 'cart_total' => $cart->total],
            'message' => 'Cantidad actualizada'
        ], Response::HTTP_OK);
    }

    public function remove(Request $request, int $itemId)
    {
        $cart     = $this->getOrCreateCart($request->user());
        $cartItem = CartItem::where('cart_id', $cart->id)->where('id', $itemId)->firstOrFail();
        $cartItem->delete();

        return response()->json(['message' => 'Producto eliminado del carrito'], Response::HTTP_OK);
    }

    public function clear(Request $request)
    {
        $this->getOrCreateCart($request->user())->clear();
        return response()->json(['message' => 'Carrito vaciado exitosamente'], Response::HTTP_OK);
    }

    private function getOrCreateCart(User $user): Cart
    {
        return Cart::firstOrCreate(['user_id' => $user->id]);
    }
}
