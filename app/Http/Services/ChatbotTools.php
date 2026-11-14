<?php

namespace App\Http\Services;

use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Str;

class ChatbotTools
{
    public function definitions(): array
    {
        return [
            $this->definition('search_products', 'Consulta hasta cinco productos, precio, stock y garantía. query debe ser un nombre o SKU corto. Para ver catálogo o una selección sin producto específico, usa query="". No busques palabras genéricas como productos, catálogo o disponibles.', ['query' => ['type' => 'string', 'maxLength' => 100]]),
            $this->definition('my_orders', 'Consulta los últimos cinco pedidos del usuario autenticado. No acepta identificadores de usuario.', []),
            $this->definition('my_cart', 'Consulta hasta diez artículos del carrito del usuario autenticado.', []),
            $this->definition('price_statistics', 'Resumen público de estadísticas: subidas, bajadas y cambios de precios. days: período entre 7 y 365, usa 30 por defecto.', ['days' => ['type' => 'integer', 'minimum' => 7, 'maximum' => 365]]),
            $this->definition('product_price_analysis', 'Historial y escenarios de precio futuro a 7 y 30 días de UN producto. Busca directamente por nombre o SKU; si es ambiguo pide aclaración. days es el historial analizado (90 por defecto, entre 7 y 365). Nunca inventes predicciones si available=false.', ['query' => ['type' => 'string', 'maxLength' => 100], 'days' => ['type' => 'integer', 'minimum' => 7, 'maximum' => 365]]),
        ];
    }

    private function definition(string $name, string $description, array $properties): array
    {
        return ['type' => 'function', 'function' => ['name' => $name, 'description' => $description,
            'parameters' => ['type' => 'object', 'properties' => (object) $properties,
                'required' => array_keys($properties), 'additionalProperties' => false]]];
    }

    public function execute(string $name, array $arguments, ?User $user): array
    {
        // Monetary values in the store are BOB, not an inferred model currency.
        return $this->executeTool($name, $arguments, $user) + ['currency' => 'BOB', 'currency_symbol' => 'Bs'];
    }

    private function executeTool(string $name, array $arguments, ?User $user): array
    {
        if ($name === 'price_statistics') {
            return app(ChatbotStatistics::class)->overview((int) ($arguments['days'] ?? 30));
        }
        if ($name === 'product_price_analysis') {
            return app(ChatbotStatistics::class)->product((string) ($arguments['query'] ?? ''), (int) ($arguments['days'] ?? 90));
        }
        if ($name === 'search_products') {
            $term = trim((string) ($arguments['query'] ?? ''));
            if (mb_strlen($term) > 100) {
                return ['error' => 'Usa una búsqueda de hasta 100 caracteres.'];
            }
            $products = Product::query()->where(function ($q) use ($term) {
                $q->where('name', 'like', '%'.$term.'%')->orWhere('sku', 'like', '%'.$term.'%');
            })->orderBy('name')->limit(5)->get(['id', 'name', 'sku', 'description', 'base_price', 'stock', 'warranty_days']);
            return ['products' => $products->map(function ($p) use ($user) {
                return ['id' => $p->id, 'name' => $p->name, 'sku' => $p->sku,
                    'description' => Str::limit(strip_tags($p->description ?? ''), 300),
                    'price' => $user ? $user->getFinalPrice($p->base_price) : $p->base_price,
                    'stock' => $p->stock, 'warranty_days' => $p->warranty_days];
            })->all(), 'limit' => 5];
        }

        if (!in_array($name, ['my_orders', 'my_cart'], true)) {
            return ['error' => 'Herramienta no permitida.'];
        }
        if (!$user) {
            return ['error' => 'Inicia sesión para consultar tu información personal.'];
        }
        if ($name === 'my_orders') {
            return ['orders' => $user->orders()->latest('id')->limit(5)
                ->get(['order_number', 'status', 'total', 'created_at'])->toArray(), 'limit' => 5];
        }
        $cart = $user->cart()->first();
        return ['items' => $cart ? $cart->items()->with('product:id,name')->orderBy('id')->limit(10)->get()
            ->map(fn ($item) => ['product' => $item->product ? $item->product->name : 'Producto no disponible',
                'quantity' => $item->quantity, 'price' => $item->price_when_added])->all() : [], 'limit' => 10];
    }
}
