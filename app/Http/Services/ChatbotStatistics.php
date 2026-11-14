<?php

namespace App\Http\Services;

use App\Http\Controllers\PriceStatsController;
use App\Http\Controllers\PriceForecastController;
use App\Models\Product;
use Illuminate\Http\Request;

class ChatbotStatistics
{
    public function overview(int $days): array
    {
        $data = app(PriceStatsController::class)->overview($this->request($days))->getData(true);
        if ($data['simulated']) {
            return ['simulated' => true, 'days' => $data['days'],
                'unavailable' => 'No hay cambios de precios reales en este período. La página muestra datos de demostración.'];
        }
        // Explicit public allowlist: never call adminOverview or send editors' names.
        return ['simulated' => false, 'days' => $data['days'], 'kpis' => $data['kpis'],
            'top_rises' => array_slice(array_values(array_filter($data['top_rises'], fn ($p) => $p['pct'] > 0)), 0, 3),
            'top_drops' => array_slice(array_values(array_filter($data['top_drops'], fn ($p) => $p['pct'] < 0)), 0, 3),
            'most_volatile' => array_slice($data['most_volatile'], 0, 3)];
    }

    public function product(string $query, int $days): array
    {
        $query = trim($query);
        if ($query === '' || mb_strlen($query) > 100) {
            return ['error' => 'Indica el nombre o SKU del producto (hasta 100 caracteres).'];
        }
        $product = Product::where('sku', $query)->orWhere('name', $query)->first();
        if (!$product) {
            $matches = Product::where('name', 'like', '%'.$query.'%')->orWhere('sku', 'like', '%'.$query.'%')
                ->orderBy('name')->limit(6)->get(['id', 'name', 'sku']);
            if ($matches->count() !== 1) {
                return ['clarification_required' => true, 'matches' => $matches->toArray(),
                    'message' => $matches->isEmpty() ? 'No se encontró ese producto.' : 'Hay varios productos. Pide el nombre exacto o SKU.'];
            }
            $product = Product::findOrFail($matches->first()->id);
        }
        $request = $this->request($days);
        $timeline = app(PriceStatsController::class)->productTimeline($request, $product)->getData(true);
        $result = ['product' => $timeline['product'], 'days' => max(7, min(365, $days)),
            'price_basis' => 'Precio base del catálogo; no incluye descuentos personales.',
            'simulated' => $timeline['simulated']];
        if ($timeline['simulated']) {
            return $result + ['forecast' => ['available' => false, 'scenarios' => [],
                'unavailable' => 'No hay historial real del producto; no se pueden estimar precios futuros.']];
        }
        $points = $timeline['points'];
        $first = $points[0]['price']; $last = $timeline['product']['current'];
        $result['history'] = ['first' => $first, 'current' => $last,
            'change_pct' => $first > 0 ? round(($last - $first) / $first * 100, 2) : null,
            'recent_points' => array_slice($points, -5)];
        $forecast = app(PriceForecastController::class)->show($request, $product, app(PriceForecastService::class))->getData(true);
        $f = $forecast['forecast'];
        if ($f['available']) {
            $result['forecast'] = ['available' => true, 'scenarios' => array_map(fn ($s) =>
                array_intersect_key($s, array_flip(['days', 'low', 'mid', 'high', 'samplePeriods'])), $f['scenarios']),
                'low_confidence' => $f['lowConfidence'], 'rate_published_at' => $f['rate_published_at'],
                'assumed_usd_exposure' => $f['elasticity'] === null ? '25%-100%; relación no comprobada.' : null,
                'source' => $forecast['source'], 'disclaimer' => $f['disclaimer']];
        } else {
            $result['forecast'] = $f;
        }
        return $result;
    }

    private function request(int $days): Request
    {
        return Request::create('/', 'GET', ['days' => max(7, min(365, $days))]);
    }
}
