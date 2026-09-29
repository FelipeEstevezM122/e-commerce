<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

// crea la tabla de rangos e inserta los cuatro niveles del sistema de fidelizacion
return new class extends Migration
{
    public function up()
    {
        Schema::create('ranks', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            // monto minimo de compras mensuales para tener este rango
            $table->decimal('monthly_minimum_purchase', 10, 2);
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // bronce es el rango inicial sin minimo, los demas requieren compras crecientes
        DB::table('ranks')->insert([
            ['name' => 'Bronce', 'monthly_minimum_purchase' => 0, 'description' => 'Rango inicial', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Plata', 'monthly_minimum_purchase' => 1000, 'description' => 'Compras > Bs. 1000', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Oro', 'monthly_minimum_purchase' => 5000, 'description' => 'Compras > Bs. 5000', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Platino', 'monthly_minimum_purchase' => 10000, 'description' => 'Compras > Bs. 10000', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(){
        Schema::dropIfExists('ranks');
    }
};