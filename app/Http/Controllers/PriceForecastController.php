<?php

namespace App\Http\Controllers;

use App\Http\Services\PriceForecastService;
use App\Models\Product;
use Illuminate\Http\Request;

class PriceForecastController extends Controller
{
    public function show(Request $request, Product $product, PriceForecastService $calculator)
    {
        $timeline = app(PriceStatsController::class)->productTimeline($request, $product)->getData(true);
        $exchange = ['simulated' => false, 'points' => []];
        // Never request external data to forecast simulated product history.
        if (!$timeline['simulated']) {
            $exchange = app(ExchangeHistoryController::class)->index(Request::create('/', 'GET', ['days' => 365]))->getData(true);
        }
        $forecast = isset($exchange['error'])
            ? ['available' => false, 'scenarios' => [], 'unavailable' => 'El historial de cotizaciones no está disponible; no puede calcularse un escenario futuro.']
            : $calculator->calculate($timeline, $exchange);
        return response()->json(['product' => $timeline['product'], 'simulated' => $timeline['simulated'],
            'forecast' => $forecast, 'source' => $exchange['source'] ?? null,
            'generated_at' => now()->toIso8601String()]);
    }
}
