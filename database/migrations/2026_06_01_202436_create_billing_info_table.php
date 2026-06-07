<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// tabla nueva que centraliza los datos de facturacion y envio
// antes estos datos estaban repetidos en cada pedido (nit, direccion, whatsapp, etc)
// ahora el usuario guarda sus direcciones aqui y las reutiliza en los pedidos
return new class extends Migration
{
    public function up()
    {
        Schema::create('billing_info', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('address')->nullable();
            $table->string('company_name', 150)->nullable();
            $table->string('nit', 20)->nullable();
            $table->string('business_name', 150)->nullable();
            $table->string('whatsapp', 20)->nullable();
            // solo una direccion puede ser la predeterminada por usuario
            $table->boolean('is_default')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('billing_info');
    }
};