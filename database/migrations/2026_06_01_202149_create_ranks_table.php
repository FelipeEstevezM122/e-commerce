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
            $table->string('name', 100);              // Nombre del rango (Bronce, Plata, etc.)
            $table->decimal('min_purchase', 10, 2);   // Monto mínimo para alcanzar el rango
            $table->decimal('discount_percentage', 5, 2)->default(0); // Porcentaje de descuento
            $table->timestamps();
        });

        // Insertar los rangos por defecto
        DB::table('ranks')->insert([
            [
                'name' => 'Bronce',
                'min_purchase' => 0,
                'discount_percentage' => 0,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Plata',
                'min_purchase' => 1000,
                'discount_percentage' => 5,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Oro',
                'min_purchase' => 5000,
                'discount_percentage' => 10,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Platino',
                'min_purchase' => 10000,
                'discount_percentage' => 15,
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