<?php

namespace Tests\Unit;

use App\Http\Services\PriceForecastService;
use Carbon\Carbon;
use PHPUnit\Framework\TestCase;

class PriceForecastTest extends TestCase
{
    private function inputs(): array
    {
        $rates = []; $history = [];
        for ($i = 0; $i < 100; $i++) {
            $rates[] = ['date' => Carbon::parse('2026-01-01', 'UTC')->addDays($i)->toDateString(),
                'sell' => 6.96 + .01 * $i + .08 * sin($i / 3)];
        }
        for ($i = 0; $i < 16; $i++) {
            $history[] = ['at' => Carbon::parse('2026-01-01T18:00:00Z')->addDays($i * 6)->toIso8601String(),
                'price' => 100 + 1.3 * $i + 2 * sin($i)];
        }
        return [['simulated' => false, 'points' => $history], ['simulated' => false, 'points' => $rates]];
    }

    public function test_matches_original_browser_calculation(): void
    {
        // Golden values obtained by executing the original JS with these inputs.
        $f = (new PriceForecastService())->calculate(...$this->inputs());
        $this->assertTrue($f['available']);
        $this->assertFalse($f['lowConfidence']);
        $this->assertEqualsWithDelta(.034064686136003705, $f['elasticity'], 1e-10);
        $this->assertSame([7, 30], array_column($f['scenarios'], 'days'));
        $this->assertSame([122.8, 129.63], array_column($f['scenarios'], 'mid'));
        $this->assertSame([120.8, 120.8], array_column($f['scenarios'], 'low'));
        $this->assertSame([124.05, 132.28], array_column($f['scenarios'], 'high'));
        $this->assertSame([94, 71], array_column($f['scenarios'], 'samplePeriods'));
    }

    public function test_refuses_simulated_data_even_if_there_are_many_points(): void
    {
        [$timeline, $exchange] = $this->inputs();
        foreach ([[$timeline + [], array_merge($exchange, ['simulated' => true])],
            [array_merge($timeline, ['simulated' => true]), $exchange]] as $inputs) {
            $f = (new PriceForecastService())->calculate(...$inputs);
            $this->assertFalse($f['available']); $this->assertSame([], $f['scenarios']);
        }
    }

    public function test_refuses_insufficient_comparable_periods(): void
    {
        [$timeline, $exchange] = $this->inputs();
        $exchange['points'] = array_slice($exchange['points'], 0, 20);
        $this->assertFalse((new PriceForecastService())->calculate($timeline, $exchange)['available']);
    }

    public function test_sparse_product_history_is_explicitly_low_confidence(): void
    {
        [$timeline, $exchange] = $this->inputs();
        $timeline['points'] = [$timeline['points'][0], end($timeline['points'])];
        $f = (new PriceForecastService())->calculate($timeline, $exchange);
        $this->assertTrue($f['available']); $this->assertTrue($f['lowConfidence']);
        $this->assertNull($f['elasticity']);
    }

    public function test_extreme_manual_change_does_not_become_a_projected_trend(): void
    {
        [$timeline, $exchange] = $this->inputs();
        $timeline['points'] = [array_merge($timeline['points'][0], ['price' => 1000]),
            array_merge(end($timeline['points']), ['price' => 10])];
        $f = (new PriceForecastService())->calculate($timeline, $exchange);
        $this->assertTrue($f['lowConfidence']); $this->assertSame(1, $f['extreme']);
        $this->assertGreaterThan(9, $f['scenarios'][0]['low']);
        $this->assertLessThan(12, $f['scenarios'][0]['high']);
    }
}
