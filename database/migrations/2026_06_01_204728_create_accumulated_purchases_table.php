<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// guarda el total de compras de cada usuario por mes
// el unique de user_id + month garantiza un solo registro por usuario por mes
// trimestre y anio no se guardan porque se calculan desde el campo month con carbon
return new class extends Migration
{
    public function up()
    {
        Schema::create('accumulated_purchases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            // se guarda el primer dia del mes: 2025-01-01, 2025-02-01, etc
            $table->date('month');
            $table->decimal('total_purchases', 10, 2)->default(0);
            // no puede haber dos registros del mismo mes para el mismo usuario
            $table->unique(['user_id', 'month']);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('accumulated_purchases');
    }
};