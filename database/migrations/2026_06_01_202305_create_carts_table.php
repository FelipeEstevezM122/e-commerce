<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// crea la tabla de carritos
// user_id es unique porque cada usuario tiene exactamente un carrito
return new class extends Migration
{
    public function up()
    {
        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            // unique garantiza que no se pueda crear un segundo carrito para el mismo usuario
            $table->foreignId('user_id')->unique()->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('carts');
    }
};