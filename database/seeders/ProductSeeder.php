<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Brand;
use App\Models\Category;

class ProductSeeder extends Seeder
{
    public function run()
    {
        // Obtener marcas y categorías existentes
        $brands = Brand::all();
        $categories = Category::all();
        
        if ($brands->isEmpty() || $categories->isEmpty()) {
            $this->command->info('Primero crea marcas y categorías');
            return;
        }
        
        // Crear 100 productos con relaciones reales
        Product::factory(100)
            ->create()
            ->each(function ($product) use ($brands, $categories) {
                // Asignar marca aleatoria
                $product->brand_id = $brands->random()->id;
                // Asignar categoría aleatoria
                $product->category_id = $categories->random()->id;
                $product->save();
            });
        
        // Productos destacados específicos
        Product::factory()->create([
            'name' => 'Laptop Gamer Pro',
            'base_price' => 1299.99,
            'stock' => 10,
            'sku' => 'LAP-001',
            'brand_id' => Brand::where('name', 'Samsung')->first()?->id,
            'category_id' => Category::where('name', 'Electrónica')->first()?->id,
        ]);
    }
}