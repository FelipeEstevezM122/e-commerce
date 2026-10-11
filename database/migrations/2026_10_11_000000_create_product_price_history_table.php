<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

//registro cada vez que cambia el base_price de un producto de catalogo
return new class extends Migration
{
    public function up()
    {
        Schema::create('product_price_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            
            $table->decimal('old_price', 10, 2);
            
            $table->decimal('new_price', 10, 2);
            
            $table->foreignId('changed_by')->nullable()->constrained('users')->nullOnDelete();
            
            $table->timestamp('changed_at');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('product_price_history');
    }
};