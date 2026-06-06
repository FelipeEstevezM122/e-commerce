<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CatalogoController extends Controller
{
    public function index(Request $request)
    {
        $q     = $request->get('q', '');
        $orden = $request->get('orden', 'default');
        $page  = $request->get('page', 1);

        // Solo cachear cuando no hay búsqueda ni filtro activo
        $usarCache = empty($q) && $orden === 'default';

        if ($usarCache) {
            $productos = Cache::remember("catalogo_page_{$page}", 300, function () {
                return Product::with('brand', 'category')
                    ->latest()
                    ->paginate(8);
            });
        } else {
            $query = Product::with('brand', 'category');

            if (!empty($q)) {
                $query->where(fn($sq) => $sq
                    ->where('name', 'LIKE', "%{$q}%")
                    ->orWhere('description', 'LIKE', "%{$q}%")
                );
            }

            match ($orden) {
                'nombre'      => $query->orderBy('name'),
                'precio_asc'  => $query->orderBy('base_price'),
                'precio_desc' => $query->orderByDesc('base_price'),
                'marca'       => $query->orderBy('brand_id'),
                'categoria'   => $query->orderBy('category_id'),
                default       => $query->latest(),
            };

            $productos = $query->paginate(8)->withQueryString();
        }

        return view('productos', compact('productos'));
    }
}