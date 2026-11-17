<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {

            // Datos del comprador
            $table->string('buyer_name', 150)->nullable();
            $table->string('buyer_ci', 30)->nullable();
            $table->string('buyer_phone', 25)->nullable();

            // Control de reserva y pago
            $table->timestamp('expires_at')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('delivered_at')->nullable();

            // Control de cancelaciones
            $table->timestamp('cancelled_at')->nullable();
            $table->string('cancellation_reason', 150)->nullable();

            // Optimizar la búsqueda de reservas vencidas
            $table->index(
                ['status', 'expires_at'],
                'orders_status_expires_idx'
            );
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {

            $table->dropIndex('orders_status_expires_idx');

            $table->dropColumn([
                'buyer_name',
                'buyer_ci',
                'buyer_phone',
                'expires_at',
                'paid_at',
                'delivered_at',
                'cancelled_at',
                'cancellation_reason'
            ]);
        });
    }
};
