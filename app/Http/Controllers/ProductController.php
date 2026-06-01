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

    //Listar todos los productos.
    public function index()
    {
        $products = Product::with('user:id,name,email')->get();
        return response()->json([
            'datos' => $products,
            'message' => 'Lista de productos'
        ], 200);
    }

    //Crear un nuevo producto.
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:120',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
        ]);

        $product = $request->user()->products()->create($validated);

        return response()->json([
            'datos' => $product->load('user:id,name'),
            'message' => 'Producto creado con éxito'
        ], Response::HTTP_CREATED);
    }

    //Ver un producto específico.
    public function show(Product $product)
    {
        return response()->json([
            'datos' => $product->load('user:id,name'),
            'message' => 'Producto mostrado con éxito'
        ], 200);
    }

    //Buscar productos por nombre o descripción.
    public function search(Request $request)
    {
        $query = $request->get('q', '');

        if (empty($query)) {
            return response()->json([
                'datos' => [],
                'message' => 'Debe proporcionar un término de búsqueda'
            ], 400);
        }

        $products = Product::with('user:id,name,email')
            ->where('name', 'LIKE', "%{$query}%")
            ->orWhere('description', 'LIKE', "%{$query}%")
            ->get();

        return response()->json([
            'datos' => $products,
            'message' => 'Resultados de búsqueda para: ' . $query
        ], 200);
    }

    //Actualizar un producto.
    public function update(Request $request, Product $product)
    {
        $this->authorize('update', $product);

        $validated = $request->validate([
            'name' => 'required|string|max:120',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
        ]);

        $product->update($validated);

        return response()->json([
            'datos' => $product->load('user:id,name'),
            'message' => 'Producto actualizado con éxito'
        ]);
    }

    //Eliminar un producto.
    public function destroy(Product $product)
    {
        $this->authorize('delete', $product); 

        $product->delete();

        return response()->json([
            'message' => 'Producto eliminado con éxito'
        ]);
    }
}