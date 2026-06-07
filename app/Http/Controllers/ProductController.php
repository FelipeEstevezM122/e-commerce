<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Cloudinary\Cloudinary;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;

class ProductController extends Controller
{
    protected Cloudinary $cloudinary;

    public function __construct()
    {
        /*
         * FIX #1: Ya NO se aplica auth:sanctum globalmente en el constructor.
         *
         * Razón: el panel admin usa sesión Laravel (guard "web"), no tokens
         * Sanctum. Si dejamos auth:sanctum aquí, los métodos store/update/destroy
         * llamados desde el blade lanzan 401 porque no hay token Bearer.
         *
         * La protección ahora vive donde corresponde:
         *   - Rutas API  (api.php)  → ya tienen middleware('auth:sanctum')
         *   - Rutas web  (web.php)  → ya tienen middleware('auth','admin')
         * Por eso no hace falta duplicar la protección aquí.
         */

        $this->cloudinary = new Cloudinary([
            'cloud' => [
                'cloud_name' => env('CLOUDINARY_CLOUD_NAME'),
                'api_key'    => env('CLOUDINARY_API_KEY'),
                'api_secret' => env('CLOUDINARY_API_SECRET'),
            ],
            'url' => ['secure' => true],
        ]);
    }

    /**
     * Vista blade del panel admin — lista paginada de productos.
     * FIX #2: se añade $categories al compact para el filtro por categoría.
     */
    public function index(Request $request)
    {
        $query = Product::with('brand', 'category');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(fn($q) => $q
                ->where('name', 'LIKE', "%{$search}%")
                ->orWhere('description', 'LIKE', "%{$search}%")
                ->orWhere('sku', 'LIKE', "%{$search}%")
            );
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('brand_id')) {
            $query->where('brand_id', $request->brand_id);
        }

        // Filtro de stock
        $stockFilter = $request->get('stock_filter');
        if ($stockFilter === 'low')       $query->whereBetween('stock', [1, 10]);
        elseif ($stockFilter === 'out')   $query->where('stock', 0);
        elseif ($stockFilter === 'ok')    $query->where('stock', '>', 10);
        elseif ($request->has('low_stock')) $query->where('stock', '<=', $request->input('threshold', 10))->where('stock', '>', 0);

        // Orden
        match ($request->get('orden', 'reciente')) {
            'nombre'      => $query->orderBy('name'),
            'precio_asc'  => $query->orderBy('base_price'),
            'precio_desc' => $query->orderByDesc('base_price'),
            'stock_asc'   => $query->orderBy('stock'),
            'stock_desc'  => $query->orderByDesc('stock'),
            default       => $query->orderBy('created_at', 'desc'),
        };

        $products      = $query->paginate(15)->withQueryString();
        $totalProducts = Product::count();
        $totalBrands   = Brand::count();
        $lowStock      = Product::where('stock', '<=', 10)->where('stock', '>', 0)->count();
        $noStock       = Product::where('stock', 0)->count();
        $categories    = Category::orderBy('name')->get();
        $brands        = Brand::orderBy('name')->get();

        if ($request->wantsJson()) {
            return response()->json([
                'datos'   => $products,
                'message' => 'Lista de productos'
            ], Response::HTTP_OK);
        }

        return view('vista_admin', compact(
            'products', 'totalProducts', 'totalBrands', 'lowStock', 'noStock', 'categories', 'brands'
        ));
    }

    /** Vista para crear producto */
    public function create()
    {
        $brands         = Brand::all();
        $categories     = Category::orderBy('name')->get();
        $totalProducts  = Product::count();
        $totalBrands    = Brand::count();
        $lowStock       = Product::where('stock', '>', 0)->where('stock', '<=', 10)->count();
        $noStock        = Product::where('stock', 0)->count();
        return view('admin.create-product', compact('brands', 'categories', 'totalProducts', 'totalBrands', 'lowStock', 'noStock'));
    }

    /** Vista para editar producto */
    public function edit(Product $product)
    {
        $brands     = Brand::all();
        $categories = Category::orderBy('name')->get();
        return view('edit', compact('product', 'brands', 'categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'          => 'required|string|max:150',
            'description'   => 'nullable|string',
            'base_price'    => 'required|numeric|min:0',
            'stock'         => 'required|integer|min:0',
            'sku'           => 'required|string|max:50|unique:products,sku',
            'warranty_days' => 'nullable|integer|min:0',
            'brand_id'      => 'nullable|exists:brands,id',
            'category_id'   => 'nullable|exists:categories,id',
            'image1'        => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',
            'image2'        => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',
            'image3'        => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',
            'image4'        => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',
        ]);

        foreach (['image1', 'image2', 'image3', 'image4'] as $field) {
            if ($request->hasFile($field)) {
                $validated[$field] = $this->uploadToCloudinary(
                    $request->file($field),
                    $validated['sku']
                );
            }
        }

        $product = Product::create($validated);

        Cache::flush();

        if ($request->wantsJson()) {
            return response()->json([
                'datos'   => $product->load('brand', 'category'),
                'message' => 'Producto creado con éxito'
            ], Response::HTTP_CREATED);
        }

        return redirect()->route('admin.products.index')
                         ->with('success', 'Producto creado con éxito');
    }

    public function show(Product $product)
    {
        return response()->json([
            'datos'   => $product->load('brand', 'category'),
            'message' => 'Producto mostrado con éxito'
        ], Response::HTTP_OK);
    }

    public function search(Request $request)
    {
        $query = $request->get('q', '');

        if (empty($query)) {
            return response()->json([
                'datos'   => [],
                'message' => 'Debe proporcionar un término de búsqueda'
            ], Response::HTTP_BAD_REQUEST);
        }

        $products = Product::with('brand', 'category')
            ->where('name', 'LIKE', "%{$query}%")
            ->orWhere('description', 'LIKE', "%{$query}%")
            ->get();

        return response()->json([
            'datos'   => $products,
            'message' => "Resultados para: {$query}"
        ], Response::HTTP_OK);
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name'          => 'sometimes|string|max:150',
            'description'   => 'nullable|string',
            'base_price'    => 'sometimes|numeric|min:0',
            'stock'         => 'sometimes|integer|min:0',
            'sku'           => 'sometimes|string|max:50|unique:products,sku,' . $product->id,
            'warranty_days' => 'nullable|integer|min:0',
            'brand_id'      => 'nullable|exists:brands,id',
            'category_id'   => 'nullable|exists:categories,id',
            'image1'        => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',
            'image2'        => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',
            'image3'        => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',
            'image4'        => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',
        ]);

        $sku = $validated['sku'] ?? $product->sku;

        foreach (['image1', 'image2', 'image3', 'image4'] as $field) {
            if ($request->hasFile($field)) {
                if ($product->$field) {
                    $this->deleteFromCloudinary($product->$field);
                }
                $validated[$field] = $this->uploadToCloudinary(
                    $request->file($field),
                    $sku
                );
            }
        }

        $product->update($validated);

        Cache::flush();

        if ($request->wantsJson()) {
            return response()->json([
                'datos'   => $product->load('brand', 'category'),
                'message' => 'Producto actualizado con éxito'
            ], Response::HTTP_OK);
        }

        return redirect()->route('admin.products.index')
                         ->with('success', 'Producto actualizado con éxito');
    }

    public function destroy(Product $product)
    {
        foreach (['image1', 'image2', 'image3', 'image4'] as $field) {
            if ($product->$field) {
                $this->deleteFromCloudinary($product->$field);
            }
        }

        $product->delete();

        Cache::flush();

        if (request()->wantsJson()) {
            return response()->json([
                'message' => 'Producto eliminado con éxito'
            ], Response::HTTP_OK);
        }

        return redirect()->route('admin.products.index')
                         ->with('success', 'Producto eliminado con éxito');
    }

    // ─── Helpers privados ─────────────────────────────────────────

    private function uploadToCloudinary($file, string $sku): string
    {
        $result = $this->cloudinary->uploadApi()->upload($file->getRealPath(), [
            'folder'    => 'casatek/productos/' . $sku,
            'public_id' => uniqid(),
        ]);

        return $result['secure_url'];
    }

    private function deleteFromCloudinary(string $url): void
    {
        try {
            if (preg_match('/\/v\d+\/(.+)\.[a-z]+$/i', $url, $matches)) {
                $this->cloudinary->uploadApi()->destroy($matches[1]);
            }
        } catch (\Exception $e) {
            // No interrumpir el flujo si falla el borrado
        }
    }
}
