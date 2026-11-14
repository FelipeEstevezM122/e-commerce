<?php

namespace Tests\Feature;

use App\Http\Services\ChatbotTools;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class ChatbotStatisticsTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        config(['database.default' => 'sqlite', 'database.connections.sqlite.database' => ':memory:',
            'services.groq.key' => 'test-key']);
        DB::purge('sqlite');
        Http::preventStrayRequests();
        Schema::create('products', function (Blueprint $table) {
            $table->id(); $table->string('name'); $table->string('sku'); $table->decimal('base_price');
        });
        Schema::create('product_price_history', function (Blueprint $table) {
            $table->id(); $table->unsignedBigInteger('product_id'); $table->decimal('old_price');
            $table->decimal('new_price'); $table->unsignedBigInteger('changed_by')->nullable();
            $table->timestamp('changed_at');
        });
        DB::table('products')->insert([
            ['id' => 1, 'name' => 'Cámara Alpha', 'sku' => 'ALPHA', 'base_price' => 100],
            ['id' => 2, 'name' => 'Cámara Beta', 'sku' => 'BETA', 'base_price' => 200],
        ]);
    }

    private function realHistory(): void
    {
        DB::table('product_price_history')->insert([
            ['product_id' => 1, 'old_price' => 80, 'new_price' => 90, 'changed_by' => 999, 'changed_at' => now()->subDays(45)],
            ['product_id' => 1, 'old_price' => 90, 'new_price' => 100, 'changed_by' => 999, 'changed_at' => now()->subDays(5)],
        ]);
    }

    private function exchangePoints(): array
    {
        $points = [];
        for ($i = 0; $i < 100; $i++) {
            $points[] = ['date' => now()->subDays(100 - $i)->toDateString(), 'sell' => 6.96 + .01 * $i + .08 * sin($i / 3)];
        }
        return $points;
    }

    public function test_simulated_statistics_are_not_sent_as_real_figures(): void
    {
        $data = app(ChatbotTools::class)->execute('price_statistics', ['days' => 30], null);
        $this->assertTrue($data['simulated']);
        $this->assertArrayNotHasKey('kpis', $data);
    }

    public function test_public_summary_excludes_administrative_information(): void
    {
        $this->realHistory();
        $data = app(ChatbotTools::class)->execute('price_statistics', ['days' => 90, 'admin' => true], null);
        $this->assertFalse($data['simulated']);
        $this->assertSame(2, $data['kpis']['changes']);
        $this->assertSame([], $data['top_drops']);
        $this->assertStringNotContainsString('changed_by', json_encode($data));
        $this->assertStringNotContainsString('999', json_encode($data));
    }

    public function test_simulated_product_has_no_forecast_and_makes_no_external_request(): void
    {
        Http::fake();
        $data = app(ChatbotTools::class)->execute('product_price_analysis', ['query' => 'BETA', 'days' => 90], null);
        $this->assertTrue($data['simulated']); $this->assertFalse($data['forecast']['available']);
        $this->assertSame([], $data['forecast']['scenarios']);
        $this->getJson('/api/price-stats/products/2/forecast?days=90')->assertOk()
            ->assertJsonPath('forecast.available', false);
        Http::assertNothingSent();
    }

    public function test_ambiguous_product_requires_clarification(): void
    {
        $data = app(ChatbotTools::class)->execute('product_price_analysis', ['query' => 'Cámara'], null);
        $this->assertTrue($data['clarification_required']);
        $this->assertCount(2, $data['matches']);
        $this->assertArrayNotHasKey('forecast', $data);
    }

    public function test_page_and_chatbot_use_identical_scenarios(): void
    {
        $this->realHistory();
        Http::fake(['api.factura.bo/*' => Http::response($this->exchangePoints())]);
        $page = $this->getJson('/api/price-stats/products/1/forecast?days=90')->assertOk()
            ->assertJsonPath('forecast.available', true)->json();
        $chat = app(ChatbotTools::class)->execute('product_price_analysis', ['query' => 'ALPHA', 'days' => 90], null);
        $this->assertTrue($chat['forecast']['available']);
        $this->assertTrue($chat['forecast']['low_confidence']);
        $this->assertEquals(array_column($page['forecast']['scenarios'], 'mid'), array_column($chat['forecast']['scenarios'], 'mid'));
        $this->assertEquals(array_column($page['forecast']['scenarios'], 'low'), array_column($chat['forecast']['scenarios'], 'low'));
        $this->assertEquals(array_column($page['forecast']['scenarios'], 'high'), array_column($chat['forecast']['scenarios'], 'high'));
    }

    public function test_exchange_failure_preserves_real_history_without_inventing_forecast(): void
    {
        $this->realHistory();
        Http::fake(['api.factura.bo/*' => Http::response([], 503)]);
        $data = app(ChatbotTools::class)->execute('product_price_analysis', ['query' => 'ALPHA'], null);
        $this->assertFalse($data['simulated']);
        $this->assertEquals(100.0, $data['history']['current']);
        $this->assertFalse($data['forecast']['available']);
        $this->assertStringContainsString('cotizaciones no está disponible', $data['forecast']['unavailable']);
        $this->assertArrayNotHasKey('diagnostic', $data['forecast']);
    }

    public function test_missing_product_forecast_is_404(): void
    {
        $this->getJson('/api/price-stats/products/999/forecast')->assertNotFound();
    }
}
