<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Product;
use Cloudinary\Cloudinary;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ProductController extends Controller
{
    protected Cloudinary $cloudinary;

    public function __construct()
    {
        // FIX: rutas web usan 'auth', rutas API usan 'auth:sanctum'
        $this->middleware('auth:sanctum')->except('index', 'show', 'search');

        $this->cloudinary = new Cloudinary([
            'cloud' => [
                'cloud_name' => env('CLOUDINARY_CLOUD_NAME'),
                'api_key'    => env('CLOUDINARY_API_KEY'),
                'api_secret' => env('CLOUDINARY_API_SECRET'),
            ],
            'url' => ['secure' => true],
        ]);
    }

    // Vista blade del panel admin
    public function index()
    {
        $products = Product::with('brand', 'category')->paginate(15);

        $totalProducts = Product::count();
        $totalBrands   = Brand::count(); // FIX: faltaba el use App\Models\Brand
        $lowStock      = Product::where('stock', '<=', 10)->where('stock', '>', 0)->count();
        $noStock       = Product::where('stock', 0)->count();

        return view('admin.products.index', compact(
            'products', 'totalProducts', 'totalBrands', 'lowStock', 'noStock'
        )); // FIX: view() no acepta código HTTP como tercer parámetro
    }

    // Vista para crear producto
    public function create()
    {
        $brands     = Brand::all();
        $categories = \App\Models\Category::all();
        return view('admin.products.create', compact('brands', 'categories'));
    }

    // Vista para editar producto
    public function edit(Product $product)
    {
        $brands     = Brand::all();
        $categories = \App\Models\Category::all();
        return view('admin.products.edit', compact('product', 'brands', 'categories'));
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

        // FIX: si viene de un form blade, redirigir; si es API, retornar JSON
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

        // FIX: responde según si es blade o API
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

        // FIX: responde según si es blade o API
        if (request()->wantsJson()) {
            return response()->json(['message' => 'Producto eliminado con éxito'], Response::HTTP_OK);
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
