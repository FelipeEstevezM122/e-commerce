<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

// crea la tabla de roles e inserta los tres roles del sistema
return new class extends Migration
{
    public function up()
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50);
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // se insertan directamente en la migracion para que siempre existan
        DB::table('roles')->insert([
            ['name' => 'cliente', 'description' => 'Cliente final', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'mayorista','description' => 'Cliente mayorista', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'admin', 'description' => 'Administrador del sistema', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(){
        Schema::dropIfExists('roles');
    }
};