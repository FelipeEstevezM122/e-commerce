<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductPriceHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Schema;

// HU-10: estadisticas y analisis de variacion de precios + dolar en tiempo real
class PriceStatsController extends Controller
{
    /* ============================================================
     |  1) DOLAR EN TIEMPO REAL
     |  CONEXION API: DolarApi (Bolivia). Se consulta desde el backend
     |  (evita CORS y esconde la fuente al navegador) y se cachea 2 min.
     |  Si la API falla se usa el ultimo valor guardado y, si no hay,
     |  el tipo de cambio oficial del BCB (fijo).
     |  OJO: el formato exacto de la respuesta no se pudo verificar en
     |  desarrollo; por eso el parseo es tolerante (ver parseDolarApi).
     ============================================================ */
    private const DOLAR_API_URL = 'https://bo.dolarapi.com/v1/dolares';

    public function exchangeRate()
    {
        $data = Cache::remember('usd_bob_live', 120, function () {
            try {
                $res = Http::timeout(5)->acceptJson()->get(self::DOLAR_API_URL);
                if ($res->ok()) {
                    $parsed = $this->parseDolarApi($res->json());
                    if ($parsed) {
                        Cache::forever('usd_bob_last', $parsed);   // copia de respaldo
                        return $parsed;
                    }
                }
            } catch (\Throwable $e) {
                // sigue al respaldo
            }
            return null;
        });

        if (!$data) {
            // Respaldo 1: ultimo valor bueno. Respaldo 2: oficial BCB.
            $data = Cache::get('usd_bob_last') ?? [
                'official' => ['buy' => 6.86, 'sell' => 6.96],
                'parallel' => null,
                'source'   => 'BCB (valor oficial fijo)',
                'updated_at' => null,
            ];
            $data['fallback'] = true;
        }
        $data['fetched_at'] = now()->toIso8601String();
        return response()->json($data);
    }

    // Convierte la respuesta de DolarApi en {official, parallel}.
    // Acepta un arreglo de objetos con claves: casa|nombre, compra, venta, fechaActualizacion.
    private function parseDolarApi($json): ?array
    {
        if (!is_array($json)) return null;
        $out = ['official' => null, 'parallel' => null, 'source' => 'DolarApi.com (Bolivia)', 'updated_at' => null];
        foreach ($json as $row) {
            if (!is_array($row)) continue;
            $name = strtolower(($row['casa'] ?? '') . ' ' . ($row['nombre'] ?? ''));
            $pair = ['buy' => (float) ($row['compra'] ?? 0), 'sell' => (float) ($row['venta'] ?? 0)];
            if ($pair['sell'] <= 0) continue;
            if (str_contains($name, 'oficial') && !$out['official']) $out['official'] = $pair;
            elseif (!$out['parallel'])                                $out['parallel'] = $pair; // blue / paralelo
            $out['updated_at'] = $out['updated_at'] ?? ($row['fechaActualizacion'] ?? null);
        }
        return ($out['official'] || $out['parallel']) ? $out : null;
    }

    /* ============================================================
     |  2) ESTADISTICAS DE VARIACION DE PRECIOS
     |  CONEXION BD: tabla product_price_history (se llena sola desde
     |  Product::booted). Cliente: GET /api/price-stats
     |  Admin:  GET /api/admin/price-stats (incluye quien hizo el cambio)
     ============================================================ */
    public function overview(Request $request)
    {
        return $this->buildOverview((int) $request->query('days', 30), false, (int) $request->query('recent_page', 1));
    }

    public function adminOverview(Request $request)
    {
        return $this->buildOverview((int) $request->query('days', 30), true, (int) $request->query('recent_page', 1));
    }

    private function buildOverview(int $days, bool $admin, int $recentPage = 1)
    {
        $days  = max(7, min($days, 365));
        $since = now()->subDays($days);

        $rows = collect();
        if (Schema::hasTable('product_price_history')) {
            $q = ProductPriceHistory::with($admin ? ['product:id,name', 'changedBy:id,name'] : ['product:id,name'])
                ->where('changed_at', '>=', $since)->orderBy('changed_at');
            $rows = $q->get();
        }

        // Sin datos reales -> SIMULADO (marcado con simulated=true para que la vista lo avise)
        $simulated = $rows->isEmpty();
        if ($simulated) {
            $rows = $this->simulateRows($days);
        }

        $changes = $rows->map(function ($r) use ($admin) {
            $old = (float) $r->old_price; $new = (float) $r->new_price;
            return [
                'product_id' => $r->product_id,
                'name'       => $r->product->name ?? ('Producto #' . $r->product_id),
                'old'        => $old,
                'new'        => $new,
                'pct'        => $old > 0 ? round(($new - $old) / $old * 100, 2) : 0,
                'at'         => \Carbon\Carbon::parse($r->changed_at)->toIso8601String(),
                'by'         => $admin ? ($r->changedBy->name ?? ($r->changed_by ? 'Usuario #' . $r->changed_by : 'Sistema')) : null,
            ];
        })->values();

        // variacion total por producto (primer precio del periodo -> ultimo)
        $perProduct = $changes->groupBy('product_id')->map(function ($g) {
            $first = $g->first(); $last = $g->last();
            return [
                'product_id' => $first['product_id'],
                'name'       => $first['name'],
                'first'      => $first['old'],
                'last'       => $last['new'],
                'pct'        => $first['old'] > 0 ? round(($last['new'] - $first['old']) / $first['old'] * 100, 2) : 0,
                'changes'    => $g->count(),
            ];
        })->values();

        // serie diaria: cambio % promedio del mercado por dia (para la grafica de picos)
        $daily = $changes->groupBy(fn ($c) => substr($c['at'], 0, 10))
            ->map(fn ($g, $d) => ['date' => $d, 'avg_pct' => round($g->avg('pct'), 2), 'changes' => $g->count()])
            ->sortKeys()->values();

        // Paginación real de los últimos cambios dentro del período seleccionado.
        $recentPerPage = 10;
        $recentTotal = $changes->count();
        $recentPages = max(1, (int) ceil($recentTotal / $recentPerPage));
        $recentPage = max(1, min($recentPage, $recentPages));
        $recent = $changes->sortByDesc('at')->values()
            ->forPage($recentPage, $recentPerPage)->values();

        return response()->json([
            'simulated' => $simulated,
            'days'      => $days,
            'kpis' => [
                'changes'          => $changes->count(),
                'products_changed' => $perProduct->count(),
                'avg_pct'          => round($changes->avg('pct') ?? 0, 2),
                'rises'            => $changes->where('pct', '>', 0)->count(),
                'drops'            => $changes->where('pct', '<', 0)->count(),
            ],
            'daily'          => $daily,
            'top_rises'      => $perProduct->sortByDesc('pct')->take(5)->values(),
            'top_drops'      => $perProduct->sortBy('pct')->take(5)->values(),
            'most_volatile'  => $perProduct->sortByDesc('changes')->take(5)->values(),
            'recent'         => $recent,
            'recent_pagination' => [
                'page' => $recentPage,
                'per_page' => $recentPerPage,
                'total' => $recentTotal,
                'last_page' => $recentPages,
            ],
            // lista para el selector de la grafica por producto
            'products'       => Product::orderBy('name')->get(['id', 'name', 'base_price']),
        ]);
    }

    // GET /api/price-stats/products/{product}  -> linea de tiempo (picos) de un producto
    public function productTimeline(Request $request, Product $product)
    {
        $days  = max(7, min((int) $request->query('days', 90), 365));
        $since = now()->subDays($days);
        $points = [];
        $simulated = false;

        if (Schema::hasTable('product_price_history')) {
            $h = ProductPriceHistory::where('product_id', $product->id)
                ->where('changed_at', '>=', $since)->orderBy('changed_at')->get();
            if ($h->isNotEmpty()) {
                $points[] = ['at' => $since->toIso8601String(), 'price' => (float) $h->first()->old_price];
                foreach ($h as $r) {
                    $points[] = ['at' => \Carbon\Carbon::parse($r->changed_at)->toIso8601String(), 'price' => (float) $r->new_price];
                }
                $points[] = ['at' => now()->toIso8601String(), 'price' => (float) $product->base_price];
            }
        }

        // Sin historial -> SIMULADO (caminata aleatoria estable por producto)
        if (!$points) {
            $simulated = true;
            mt_srand($product->id * 7919);
            $price = (float) $product->base_price; $steps = 14; $series = [];
            for ($i = $steps; $i >= 0; $i--) {
                $series[] = ['at' => now()->subDays((int) ($i * $days / $steps))->toIso8601String(), 'price' => round($price, 2)];
                $price = max(1, $price * (1 + (mt_rand(-80, 80) / 1000)));   // +-8% por paso
            }
            $last = end($series)['price'] ?: 1; $k = (float) $product->base_price / $last;
            $points = array_map(fn ($p) => ['at' => $p['at'], 'price' => round($p['price'] * $k, 2)], $series);
        }

        return response()->json([
            'simulated' => $simulated,
            'product'   => ['id' => $product->id, 'name' => $product->name, 'current' => (float) $product->base_price],
            'points'    => $points,
        ]);
    }

    // DATOS SIMULADOS: se usan solo mientras product_price_history esta vacia.
    // Para quitarlos, borre este metodo y el "if ($simulated)" de buildOverview.
    private function simulateRows(int $days)
    {
        mt_srand(2026);
        $out = collect();
        foreach (Product::orderBy('id')->limit(8)->get() as $p) {
            $price = (float) $p->base_price ?: 100.0;
            for ($i = 0; $i < mt_rand(2, 5); $i++) {
                $new = round($price * (1 + mt_rand(-120, 120) / 1000), 2);
                $out->push((object) [
                    'product_id' => $p->id, 'product' => $p, 'changedBy' => null, 'changed_by' => null,
                    'old_price' => $price, 'new_price' => $new,
                    'changed_at' => now()->subDays(mt_rand(0, $days - 1))->subHours(mt_rand(0, 23)),
                ]);
                $price = $new;
            }
        }
        return $out->sortBy('changed_at')->values();
    }
}
