<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// tabla pivote de la relacion muchos a muchos entre usuarios y roles
// el unique de user_id + role_id evita que se asigne el mismo rol dos veces al mismo usuario
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_role', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('role_id')->constrained('roles')->onDelete('cascade');
            // fecha en que se asigno el rol al usuario
            $table->date('assigned_at')->default(now());
            $table->timestamps();
            // no se puede asignar el mismo rol dos veces al mismo usuario
            $table->unique(['user_id', 'role_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_role');
    }
};