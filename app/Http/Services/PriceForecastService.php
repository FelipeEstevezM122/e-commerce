<?php

namespace App\Http\Services;

use Carbon\Carbon;

// Shared by the statistics page and the chatbot. This is an indicative scenario,
// not a calibrated probability interval or a guaranteed future price.
class PriceForecastService
{
    public function calculate(array $timeline, array $exchange): array
    {
        if (($timeline['simulated'] ?? true) || ($exchange['simulated'] ?? true)) {
            return $this->unavailable('Se necesitan historiales reales; no se pronostica con datos simulados.');
        }
        $history = [];
        foreach ($timeline['points'] ?? [] as $point) {
            try { $at = Carbon::parse($point['at'])->getTimestampMs(); }
            catch (\Throwable $e) { continue; }
            $price = (float) ($point['price'] ?? 0);
            if (is_finite($price) && $price > 0) $history[] = ['at' => $at, 'price' => $price];
        }
        usort($history, fn ($a, $b) => $a['at'] <=> $b['at']);
        $byDate = [];
        foreach ($exchange['points'] ?? [] as $point) {
            $date = substr((string) ($point['date'] ?? ''), 0, 10);
            $sell = (float) ($point['sell'] ?? 0);
            if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date) || !is_finite($sell) || $sell <= 0) continue;
            try { $at = Carbon::parse($date.'T12:00:00Z')->getTimestampMs(); }
            catch (\Throwable $e) { continue; }
            $byDate[$date] = ['at' => $at, 'sell' => $sell];
        }
        ksort($byDate);
        $rates = array_values($byDate);
        if (!$history || count($rates) < 20) {
            return $this->unavailable('Faltan cotizaciones históricas del dólar o precios reales del producto.');
        }
        $steps = []; $extreme = 0;
        for ($i = 1; $i < count($history); $i++) {
            $delta = ($history[$i]['at'] - $history[$i - 1]['at']) / 86400000;
            if ($delta <= 0) continue;
            $raw = log($history[$i]['price'] / $history[$i - 1]['price']);
            if (abs($raw) > log(1.5)) $extreme++;
            if ($delta < 1 || abs($raw) > log(1.5)) continue;
            $start = $this->findRate($rates, $history[$i - 1]['at']);
            $end = $this->findRate($rates, $history[$i]['at']);
            $steps[] = ['daily' => $raw / $delta, 'usd' => $start && $end ? log($end / $start) / $delta : null];
        }
        $enough = count($steps) >= 8 && (end($history)['at'] - $history[0]['at']) / 86400000 >= 30;
        $daily = array_column($steps, 'daily');
        $drift = $enough ? $this->clamp($this->median($daily), -.005, .005) : 0;
        $median = $this->median($daily);
        $vol = $enough ? $this->clamp($this->median(array_map(fn ($v) => abs($v - $median), $daily)) * 1.4826, 0, .02) : 0;
        $elasticity = null;
        $pairs = array_values(array_filter($steps, fn ($p) => $p['usd'] !== null && abs($p['usd']) > .0001));
        if ($enough && count($pairs) >= 8) {
            $xs = array_column($pairs, 'usd'); $ys = array_column($pairs, 'daily');
            $mx = $this->median($xs); $my = $this->median($ys);
            $denom = 0; $numerator = 0;
            foreach ($xs as $i => $x) {
                $denom += ($x - $mx) ** 2;
                $numerator += ($x - $mx) * ($ys[$i] - $my);
            }
            if ($denom > 1e-7) $elasticity = $this->clamp($numerator / $denom, 0, 1.5);
        }
        $last = end($history)['price']; $scenarios = [];
        foreach ([7, 30] as $horizon) {
            $returns = $this->rollingReturns($rates, $horizon);
            if (count($returns) < 20) continue;
            $usdLow = $this->quantile($returns, .05);
            $usdMid = $this->quantile($returns, .5);
            $usdHigh = $this->quantile($returns, .95);
            $weights = $elasticity === null ? [.25, 1] : [$elasticity];
            $base = log($last) + $this->clamp($drift * $horizon, -.15, .15);
            $residual = $vol * sqrt($horizon) * 1.28;
            $mid = exp($base + $usdMid * ($elasticity ?? .625));
            $low = max(.01, min(array_merge([$last, $mid], array_map(fn ($w) => exp($base + $usdLow * $w - $residual), $weights))));
            $high = max(array_merge([$last, $mid], array_map(fn ($w) => exp($base + $usdHigh * $w + $residual), $weights)));
            if (!is_finite($mid) || !is_finite($low) || !is_finite($high)) continue;
            $scenarios[] = ['days' => $horizon, 'mid' => round($mid, 2), 'low' => round($low, 2),
                'high' => round($high, 2), 'samplePeriods' => count($returns), 'usdLow' => $usdLow, 'usdHigh' => $usdHigh];
        }
        if (!$scenarios) return $this->unavailable('No hay suficientes períodos comparables para calcular los escenarios.');
        return ['available' => true, 'last' => $last, 'points' => count($history), 'ratePoints' => count($rates),
            'extreme' => $extreme, 'elasticity' => $elasticity, 'scenarios' => $scenarios,
            'lowConfidence' => !$enough || $extreme > 0 || $elasticity === null, 'lastRate' => end($rates),
            'rate_published_at' => array_key_last($byDate),
            'disclaimer' => 'Rango orientativo basado en movimientos históricos, no es un precio garantizado ni una probabilidad calibrada.'];
    }

    private function unavailable(string $reason): array
    {
        return ['available' => false, 'unavailable' => $reason, 'scenarios' => []];
    }

    private function findRate(array $rates, int $at): ?float
    {
        $chosen = null;
        foreach ($rates as $rate) { if ($rate['at'] > $at) break; $chosen = $rate['sell']; }
        return $chosen;
    }

    private function rollingReturns(array $rates, int $horizon): array
    {
        $result = [];
        for ($i = 1; $i < count($rates); $i++) {
            $target = $rates[$i]['at'] - $horizon * 86400000;
            $closest = -1; $distance = INF;
            for ($j = $i - 1; $j >= 0; $j--) {
                $diff = abs($rates[$j]['at'] - $target);
                if ($diff < $distance) { $distance = $diff; $closest = $j; }
                if ($rates[$j]['at'] < $target - 86400000) break;
            }
            if ($closest < 0 || $distance > 86400000) continue;
            $days = ($rates[$i]['at'] - $rates[$closest]['at']) / 86400000;
            if ($days <= 0) continue;
            $change = log($rates[$i]['sell'] / $rates[$closest]['sell']) * $horizon / $days;
            if (is_finite($change)) $result[] = $change;
        }
        return $result;
    }

    private function median(array $values): float { return $this->quantile($values, .5); }
    private function clamp(float $v, float $min, float $max): float { return max($min, min($max, $v)); }
    private function quantile(array $values, float $p): float
    {
        if (!$values) return 0;
        sort($values, SORT_NUMERIC);
        $pos = (count($values) - 1) * $p; $i = (int) floor($pos);
        return $values[$i] + ($values[min($i + 1, count($values) - 1)] - $values[$i]) * ($pos - $i);
    }
}
