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
                $product->brand_id    = $brands->random()->id;
                $product->category_id = $categories->random()->id;
                $product->save();
            });

        // Productos destacados específicos
        Product::firstOrCreate(
            ['sku' => 'LAP-001'],
            [
                'name'        => 'Laptop Gamer Pro',
                'base_price'  => 1299.99,
                'stock'       => 10,
                'brand_id'    => Brand::where('name', 'Samsung')->first()?->id ?? $brands->random()->id,
                'category_id' => Category::where('name', 'Electrónica')->first()?->id ?? $categories->random()->id,
            ]
        );
    }
}
