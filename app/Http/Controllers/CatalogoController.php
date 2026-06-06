<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class CatalogoController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('brand', 'category');

        // Búsqueda
        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(fn($sq) => $sq
                ->where('name', 'LIKE', "%{$q}%")
                ->orWhere('description', 'LIKE', "%{$q}%")
            );
        }

        // Ordenamiento
        match ($request->get('orden')) {
            'nombre'      => $query->orderBy('name'),
            'precio_asc'  => $query->orderBy('base_price'),
            'precio_desc' => $query->orderByDesc('base_price'),
            'marca'       => $query->orderBy('brand_id'),
            'categoria'   => $query->orderBy('category_id'),
            default       => $query->latest(),
        };

        $productos = $query->paginate(12)->withQueryString();

        return view('productos', compact('productos'));
    }
}