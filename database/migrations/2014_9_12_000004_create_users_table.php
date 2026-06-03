<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->string('access_code', 10)->nullable();
            $table->foreignId('rank_id')->nullable()->constrained('ranks')->nullOnDelete();
            $table->rememberToken();
            $table->timestamps();
            // ELIMINADO: user_type  → usar roles para determinar tipo de usuario
            // ELIMINADO: address    → movido a billing_info
            // ELIMINADO: company_name → movido a billing_info
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
};
