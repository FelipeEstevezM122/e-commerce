<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// crea la tabla de usuarios
// el tipo de usuario se maneja con roles, no con un campo user_type
// la direccion y empresa se guardan en billing_info, no aqui
return new class extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('phone', 20)->nullable();
            $table->string('whatsapp', 20)->nullable();
            // codigo opcional para acceso especial o mayorista
            $table->string('access_code', 10)->nullable();
            // si el rango se elimina, el usuario queda sin rango (nullOnDelete)
            $table->foreignId('rank_id')->nullable()->constrained('ranks')->nullOnDelete();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
};