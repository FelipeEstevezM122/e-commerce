<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// crea la tabla de pedidos
// billing_info_id reemplaza los campos de facturacion que antes estaban aqui directamente
return new class extends Migration
{
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            // referencia a la direccion usada en este pedido, si se borra queda null
            $table->foreignId('billing_info_id')->nullable()->constrained('billing_info')->nullOnDelete();
            // numero legible del pedido tipo ORD-00000001
            $table->string('order_number', 20)->unique();
            $table->decimal('total', 10, 2);
            // flujo del pedido: pending -> paid -> shipped -> delivered o cancelled
            $table->enum('status', ['pending', 'paid', 'shipped', 'delivered', 'cancelled'])->default('pending');
            $table->string('payment_method', 50)->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('orders');
    }
};