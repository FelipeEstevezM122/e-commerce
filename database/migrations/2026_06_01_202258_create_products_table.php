<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// crea la tabla de productos del catalogo
// si se elimina la marca o categoria, el producto queda sin ella (nullOnDelete)
return new class extends Migration
{
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150);
            $table->text('description')->nullable();
            $table->decimal('base_price', 10, 2);
            $table->integer('stock')->default(0);
            // imagen original, despues se agregaron image1-image4 en otra migracion
            $table->string('image', 255)->nullable();
            // codigo unico del producto para identificarlo internamente
            $table->string('sku', 50)->unique();
            $table->integer('warranty_days')->nullable();
            $table->foreignId('brand_id')->nullable()->constrained('brands')->nullOnDelete();
            $table->foreignId('category_id')->nullable()->constrained('categories')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('products');
    }
};