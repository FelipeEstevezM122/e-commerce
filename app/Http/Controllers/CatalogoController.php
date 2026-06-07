<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class CatalogoController extends Controller
{
    public function index(Request $request)
    {
        $q           = $request->get('q', '');
        $orden       = $request->get('orden', 'default');
        $categoryId  = $request->get('category_id');
        $brandId     = $request->get('brand_id');
        $page        = $request->get('page', 1);
        $perPage     = 8;

        // Solo cachear cuando no hay ningún filtro activo
        $sinFiltros = empty($q) && $orden === 'default'
                      && empty($categoryId) && empty($brandId);

        if ($sinFiltros) {
            $productos = Cache::remember("catalogo_page_{$page}", 300, function () use ($perPage) {
                return Product::with('brand', 'category')
                    ->latest()
                    ->paginate($perPage);
            });
        } else {
            // Usar procedimiento almacenado sp_filtrar_productos
            $offset = ($page - 1) * $perPage;

            try {
                $rows = DB::select('CALL sp_filtrar_productos(?, ?, ?, ?, ?, ?)', [
                    $q ?: null,
                    $categoryId ?: null,
                    $brandId    ?: null,
                    $orden,
                    $perPage + 1,
                    $offset,
                ]);
            } catch (\Exception $e) {
                // Fallback Eloquent si el SP no está disponible
                $query = Product::with('brand', 'category');
                if ($q)          $query->where('name', 'like', "%{$q}%");
                if ($categoryId) $query->where('category_id', $categoryId);
                if ($brandId)    $query->where('brand_id', $brandId);
                match ($orden) {
                    'nombre'      => $query->orderBy('name'),
                    'precio_asc'  => $query->orderBy('base_price'),
                    'precio_desc' => $query->orderByDesc('base_price'),
                    'marca'       => $query->join('brands','brands.id','=','products.brand_id')->orderBy('brands.name'),
                    'categoria'   => $query->join('categories','categories.id','=','products.category_id')->orderBy('categories.name'),
                    default       => $query->latest(),
                };
                $productos   = $query->paginate($perPage);
                $categories  = Cache::remember('catalogo_categories', 600, fn() => Category::orderBy('name')->get());
                $brands      = Cache::remember('catalogo_brands',     600, fn() => Brand::orderBy('name')->get());
                return view('productos', compact('productos', 'categories', 'brands'));
            }

            // Total para paginación
            $totalRows = DB::selectOne("
                SELECT COUNT(*) AS total
                FROM products p
                WHERE
                    (? = '' OR p.name LIKE CONCAT('%', ?, '%'))
                    AND (? IS NULL OR p.category_id = ?)
                    AND (? IS NULL OR p.brand_id    = ?)
            ", [
                $q ?: '', $q ?: '',
                $categoryId ?: null, $categoryId ?: null,
                $brandId    ?: null, $brandId    ?: null,
            ]);
            $total = $totalRows->total ?? 0;

            // Transformar rows a colección con relaciones simuladas
            $items = collect($rows)->take($perPage)->map(function ($row) {
                $product                = new Product((array) $row);
                $product->id            = $row->id;
                $product->name          = $row->name;
                $product->description   = $row->description;
                $product->base_price    = $row->base_price;
                $product->stock         = $row->stock;
                $product->image1        = $row->image1;
                $product->image2        = $row->image2;
                $product->image3        = $row->image3;
                $product->image4        = $row->image4;

                $brand            = new \App\Models\Brand();
                $brand->name      = $row->brand_name ?? 'Sin marca';
                $product->setRelation('brand', $brand);

                $category         = new \App\Models\Category();
                $category->name   = $row->category_name ?? 'General';
                $product->setRelation('category', $category);

                return $product;
            });

            $productos = new \Illuminate\Pagination\LengthAwarePaginator(
                $items,
                $total,
                $perPage,
                $page,
                ['path' => $request->url(), 'query' => $request->query()]
            );
        }

        // Datos para los selectores de filtro
        $categories = Cache::remember('catalogo_categories', 600, fn() => Category::orderBy('name')->get());
        $brands     = Cache::remember('catalogo_brands',     600, fn() => Brand::orderBy('name')->get());

        return view('productos', compact('productos', 'categories', 'brands'));
    }
}
