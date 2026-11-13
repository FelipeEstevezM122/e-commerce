<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use RuntimeException;
use Throwable;

class ExchangeHistoryController extends Controller
{
    private const URL = 'https://api.factura.bo/ExchangeRate/history';

    public function index(Request $request): JsonResponse
    {
        $days = max(7, min(365, (int) $request->query('days', 90)));
        $cacheKey = 'casatek_bcb_history_v3_' . $days;

        try {
            $result = Cache::remember($cacheKey, now()->addHours(6), function () use ($days) {
                $errors = [];
                // El proveedor puede limitar el parámetro limit. No pedir days+7 cuando days=365.
                foreach (array_unique([$days, min($days, 90), min($days, 30)]) as $limit) {
                    try {
                        $response = Http::acceptJson()->timeout(12)->get(self::URL, ['limit' => $limit]);
                        if (!$response->successful()) {
                            throw new RuntimeException('HTTP ' . $response->status());
                        }
                        $json = $response->json();
                        if (!is_array($json)) {
                            throw new RuntimeException('JSON inválido');
                        }
                        if (($json['ok'] ?? true) === false) {
                            throw new RuntimeException('El proveedor devolvió ok=false');
                        }
                        $records = $this->findRecords($json);
                        $points = $this->normalize($records);
                        if (!$points) {
                            throw new RuntimeException('Respuesta sin registros reconocibles; claves principales: ' . implode(',', array_slice(array_keys($json), 0, 8)));
                        }
                        return ['points' => $points, 'requested_limit' => $limit];
                    } catch (Throwable $e) {
                        $errors[] = 'limit=' . $limit . ': ' . $e->getMessage();
                        Log::warning('CASATEK: error al recuperar historial de cambio', [
                            'limit' => $limit, 'reason' => $e->getMessage(),
                        ]);
                    }
                }
                throw new RuntimeException(implode(' | ', $errors));
            });

            $points = $result['points'];
            return response()->json([
                'source' => 'BCB TCO público (replicado por api.factura.bo)',
                'type' => 'tco_publico',
                'points' => $points,
                'last_published_at' => $points[count($points) - 1]['date'],
                'simulated' => false,
                'requested_days' => $days,
                'available_days' => count($points),
                'provider_limit' => $result['requested_limit'],
            ]);
        } catch (Throwable $e) {
            Log::error('CASATEK: historial BCB no disponible', ['reason' => $e->getMessage()]);
            return response()->json([
                'source' => 'BCB', 'type' => 'tco_publico', 'points' => [],
                'error' => 'Historial de cotizaciones no disponible; no se emitirán predicciones basadas en el dólar.',
                'error_code' => 'EXCHANGE_HISTORY_UNAVAILABLE',
                'diagnostic' => config('app.debug') ? $e->getMessage() : 'Revisar storage/logs/laravel.log',
            ], 503);
        }
    }

    private function findRecords(array $json): array
    {
        // Localiza listas en envelopes como {datos:{historial:[...]}}.
        if (array_is_list($json)) return $json;
        foreach (['datos', 'data', 'history', 'historial', 'items', 'cotizaciones', 'records', 'results', 'result', 'tasas'] as $key) {
            if (isset($json[$key]) && is_array($json[$key])) {
                $found = $this->findRecords($json[$key]);
                if ($found) return $found;
            }
        }
        return [];
    }

    private function normalize(array $records): array
    {
        $byDate = [];
        foreach ($records as $item) {
            if (!is_array($item)) continue;
            $dateRaw = $item['fecha'] ?? $item['date'] ?? $item['vigencia']
                ?? $item['fecha_actualizacion'] ?? $item['created_at'] ?? $item['fechaRegistro'] ?? null;
            $rateRaw = $item['usd_bob'] ?? $item['tipo_cambio'] ?? $item['tipoCambio']
                ?? $item['valor'] ?? $item['venta'] ?? $item['sell'] ?? $item['tco'] ?? null;
            if (is_array($rateRaw)) {
                $rateRaw = $rateRaw['venta'] ?? $rateRaw['sell'] ?? $rateRaw['usd_bob'] ?? null;
            }
            if (is_array($item['datos'] ?? null)) {
                $rateRaw ??= $item['datos']['usd_bob'] ?? $item['datos']['venta'] ?? null;
            }
            if (!$dateRaw || !is_numeric($rateRaw)) continue;
            try { $date = Carbon::parse($dateRaw)->toDateString(); }
            catch (Throwable $e) { continue; }
            $rate = (float) $rateRaw;
            if ($rate <= 0 || $rate > 1000) continue;
            $byDate[$date] = ['date' => $date, 'sell' => $rate];
        }
        ksort($byDate);
        return array_values($byDate);
    }
}
