<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('orders')
            ->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('tickets')
                    ->whereColumn('tickets.order_id', 'orders.id');
            })
            ->orderBy('id')
            ->chunkById(100, function ($orders) {
                $tickets = $orders->map(function ($order) {
                    $issuedAt = $order->created_at ?? now()->toDateTimeString();

                    return [
                        'order_id' => $order->id,
                        'ticket_number' => 'TKT-' . date('Ymd', strtotime($issuedAt)) . '-' . Str::upper(Str::random(12)),
                        'issued_at' => $issuedAt,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                });

                DB::table('tickets')->insert($tickets->all());
            });
    }

    public function down(): void
    {
        // Existing ticket rows must not be deleted when rolling back this backfill.
    }
};
