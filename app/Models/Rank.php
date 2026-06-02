<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ranks', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);                       // Nombre del rango
            $table->decimal('monthly_minimum_purchase', 10, 2); // Compra mínima mensual
            $table->text('description')->nullable();            // Descripción del rango
            $table->timestamps();
        });

        // Insertar rangos por defecto
        DB::table('ranks')->insert([
            [
                'name' => 'Bronce',
                'monthly_minimum_purchase' => 0,
                'description' => 'Rango inicial para nuevos clientes',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Plata',
                'monthly_minimum_purchase' => 1000,
                'description' => 'Clientes con compras mensuales superiores a Bs. 1000',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Oro',
                'monthly_minimum_purchase' => 5000,
                'description' => 'Clientes con compras mensuales superiores a Bs. 5000',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Platino',
                'monthly_minimum_purchase' => 10000,
                'description' => 'Clientes con compras mensuales superiores a Bs. 10000',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ranks');
    }
};