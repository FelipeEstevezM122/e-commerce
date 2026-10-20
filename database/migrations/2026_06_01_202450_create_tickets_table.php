<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// crea la tabla de tickets de compra
// order_id es unique porque cada pedido solo puede tener un ticket
// la aplicacion crea el ticket junto al pedido; el trigger legado cubre pedidos antiguos
return new class extends Migration
{
    public function up()
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            // un pedido solo puede tener un ticket
            $table->foreignId('order_id')->unique()->constrained('orders')->onDelete('cascade');
            $table->string('ticket_number', 50)->unique();
            $table->timestamp('issued_at')->default(now());
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tickets');
    }
};