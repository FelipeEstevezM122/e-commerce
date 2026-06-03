<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// TABLA NUEVA: separa los datos de facturación del usuario
// Soluciona: nit, business_name, shipping_address, customer_whatsapp repetidos en cada order
return new class extends Migration
{
    public function up()
    {
        Schema::create('billing_info', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('address')->nullable();         // dirección de envío
            $table->string('company_name', 150)->nullable(); // empresa/nombre comercial
            $table->string('nit', 20)->nullable();         // NIT fiscal
            $table->string('business_name', 150)->nullable(); // razón social
            $table->string('whatsapp', 20)->nullable();    // whatsapp de contacto
            $table->boolean('is_default')->default(false); // dirección principal
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('billing_info');
    }
};
