<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum')->except('index', 'show', 'search');
    }

    public function index()
    {
        // FIX: era with('user') → Product no tiene relación user; usa brand y category
        $products = Product::with('brand', 'category')->get();
        return response()->json(['datos' => $products, 'message' => 'Lista de productos'], Response::HTTP_OK);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'         => 'required|string|max:150',
            'description'  => 'nullable|string',
            'base_price'   => 'required|numeric|min:0', // FIX: era 'price' → es base_price
            'stock'        => 'required|integer|min:0',
            'sku'          => 'required|string|max:50|unique:products,sku',
            'warranty_days'=> 'nullable|integer|min:0',
            'brand_id'     => 'nullable|exists:brands,id',
            'category_id'  => 'nullable|exists:categories,id',
            'image'        => 'nullable|string',
        ]);

        $product = Product::create($validated);

        return response()->json([
            'datos'   => $product->load('brand', 'category'),
            'message' => 'Producto creado con éxito'
        ], Response::HTTP_CREATED);
    }

    public function show(Product $product)
    {
        return response()->json([
            'datos'   => $product->load('brand', 'category'), // FIX: era 'user'
            'message' => 'Producto mostrado con éxito'
        ], Response::HTTP_OK);
    }

    public function search(Request $request)
    {
        $query = $request->get('q', '');

        if (empty($query)) {
            return response()->json(['datos' => [], 'message' => 'Debe proporcionar un término de búsqueda'], Response::HTTP_BAD_REQUEST);
        }

        $products = Product::with('brand', 'category')
            ->where('name', 'LIKE', "%{$query}%")
            ->orWhere('description', 'LIKE', "%{$query}%")
            ->get();

        return response()->json(['datos' => $products, 'message' => "Resultados para: {$query}"], Response::HTTP_OK);
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name'         => 'sometimes|string|max:150',
            'description'  => 'nullable|string',
            'base_price'   => 'sometimes|numeric|min:0', // FIX: era 'price'
            'stock'        => 'sometimes|integer|min:0',
            'sku'          => 'sometimes|string|max:50|unique:products,sku,' . $product->id,
            'warranty_days'=> 'nullable|integer|min:0',
            'brand_id'     => 'nullable|exists:brands,id',
            'category_id'  => 'nullable|exists:categories,id',
            'image'        => 'nullable|string',
        ]);

        $product->update($validated);

        return response()->json([
            'datos'   => $product->load('brand', 'category'),
            'message' => 'Producto actualizado con éxito'
        ], Response::HTTP_OK);
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return response()->json(['message' => 'Producto eliminado con éxito'], Response::HTTP_OK);
    }
}
