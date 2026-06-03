<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('accumulated_purchases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->date('month'); // primer día del mes: 2025-01-01
            // ELIMINADO: quarter → se calcula desde month con Carbon::parse($month)->quarter
            // ELIMINADO: year    → se calcula desde month con Carbon::parse($month)->year
            $table->decimal('total_purchases', 10, 2)->default(0);
            $table->unique(['user_id', 'month']); // un registro por usuario por mes
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('accumulated_purchases');
    }
};
