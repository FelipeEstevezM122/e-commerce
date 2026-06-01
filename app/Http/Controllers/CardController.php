<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    public function __construct()
    {
        // Solo usuarios autenticados pueden manejar su carrito
        $this->middleware('auth:sanctum');
    }

    /**
     * Ver el carrito del usuario autenticado.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        
        // Obtener o crear el carrito del usuario
        $cart = $this->getOrCreateCart($user);
        
        // Cargar los items con sus productos
        $cart->load('items.product');
        
        return response()->json([
            'datos' => [
                'cart_id' => $cart->id,
                'items' => $cart->items,
                'total_items' => $cart->total_items, // Usando el accessor del modelo
                'total_price' => $cart->total, // Usando el accessor del modelo
                'is_empty' => $cart->isEmpty(),
            ],
            'message' => 'Carrito actual'
        ], Response::HTTP_OK);
    }

    /**
     * Agregar un producto al carrito.
     * 
     * Espera JSON:
     * {
     *   "product_id": 1,
     *   "quantity": 2
     * }
     */
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);
        
        $user = $request->user();
        $productId = $request->product_id;
        $quantity = $request->quantity;
        
        // Verificar stock disponible
        $product = Product::findOrFail($productId);
        if ($product->stock < $quantity) {
            return response()->json([
                'message' => "Stock insuficiente. Disponible: {$product->stock}"
            ], Response::HTTP_BAD_REQUEST);
        }
        
        // Obtener o crear el carrito del usuario
        $cart = $this->getOrCreateCart($user);
        
        // Verificar si el producto ya está en el carrito
        $cartItem = CartItem::where('cart_id', $cart->id)
            ->where('product_id', $productId)
            ->first();
        
        if ($cartItem) {
            // Actualizar cantidad existente (verificar stock nuevamente)
            $newQuantity = $cartItem->quantity + $quantity;
            if ($product->stock < $newQuantity) {
                return response()->json([
                    'message' => "Stock insuficiente. Ya tienes {$cartItem->quantity} en el carrito, solo puedes agregar " . ($product->stock - $cartItem->quantity) . " más"
                ], Response::HTTP_BAD_REQUEST);
            }
            $cartItem->update(['quantity' => $newQuantity]);
        } else {
            // Crear nuevo item en el carrito con el precio actual del producto
            CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $productId,
                'quantity' => $quantity,
                'price_when_added' => $product->price, // Guardamos el precio actual
            ]);
        }
        
        // Recargar el carrito con los items actualizados
        $cart->load('items.product');
        
        return response()->json([
            'datos' => [
                'cart' => [
                    'cart_id' => $cart->id,
                    'items' => $cart->items,
                    'total_items' => $cart->total_items,
                    'total_price' => $cart->total,
                ],
                'added_product' => [
                    'product_id' => $product->id,
                    'name' => $product->name,
                    'quantity' => $quantity,
                    'price_when_added' => $product->price,
                ]
            ],
            'message' => 'Producto agregado al carrito'
        ], Response::HTTP_CREATED);
    }
    
    /**
     * Actualizar cantidad de un producto en el carrito.
     * 
     * Espera JSON:
     * {
     *   "quantity": 5
     * }
     */
    public function update(Request $request, $itemId)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);
        
        $user = $request->user();
        $quantity = $request->quantity;
        
        // Verificar que el item pertenezca al usuario
        $cart = $this->getOrCreateCart($user);
        $cartItem = CartItem::where('cart_id', $cart->id)
            ->where('id', $itemId)
            ->firstOrFail();
        
        // Verificar stock del producto
        $product = Product::findOrFail($cartItem->product_id);
        if ($product->stock < $quantity) {
            return response()->json([
                'message' => "Stock insuficiente. Disponible: {$product->stock}"
            ], Response::HTTP_BAD_REQUEST);
        }
        
        $cartItem->update(['quantity' => $quantity]);
        $cartItem->load('product');
        
        // Recargar el carrito para obtener el total actualizado
        $cart->load('items.product');
        
        return response()->json([
            'datos' => [
                'cart_item' => $cartItem,
                'item_total' => $cartItem->total, // Usando el accessor del modelo CartItem
                'cart_total' => $cart->total,
            ],
            'message' => 'Cantidad actualizada'
        ], Response::HTTP_OK);
    }
    
    /**
     * Quitar un producto específico del carrito.
     */
    public function remove(Request $request, $itemId)
    {
        $user = $request->user();
        
        // Verificar que el item pertenezca al usuario
        $cart = $this->getOrCreateCart($user);
        $cartItem = CartItem::where('cart_id', $cart->id)
            ->where('id', $itemId)
            ->firstOrFail();
        
        $cartItem->delete();
        
        return response()->json([
            'message' => 'Producto eliminado del carrito'
        ], Response::HTTP_OK);
    }
    
    /**
     * Vaciar completamente el carrito (usando el método clear del modelo Cart).
     */
    public function clear(Request $request)
    {
        $user = $request->user();
        $cart = $this->getOrCreateCart($user);
        
        // Usar el método clear del modelo Cart
        $cart->clear();
        
        return response()->json([
            'message' => 'Carrito vaciado exitosamente'
        ], Response::HTTP_OK);
    }
    
    /**
     * Obtener o crear el carrito del usuario.
     */
    private function getOrCreateCart($user)
    {
        return Cart::firstOrCreate(
            ['user_id' => $user->id],
            ['user_id' => $user->id]
        );
    }
}