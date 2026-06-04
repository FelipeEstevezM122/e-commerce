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
        $brands     = Brand::all();
        $categories = Category::all();

        if ($brands->isEmpty() || $categories->isEmpty()) {
            $this->command->info('Primero crea marcas y categorías');
            return;
        }

        Product::factory(100)->create([
            'brand_id'    => fn() => $brands->random()->id,
            'category_id' => fn() => $categories->random()->id,
        ]);

        // Producto destacado específico
        Product::firstOrCreate(
            ['sku' => 'LAP-001'],
            [
                'name'        => 'Laptop Gamer Pro',
                'base_price'  => 1299.99,
                'stock'       => 10,
                'image1'      => 'https://res.cloudinary.com/duzht7zvr/image/upload/v1/samples/laptop.jpg',
                'brand_id'    => Brand::where('name', 'Samsung')->first()?->id ?? $brands->random()->id,
                'category_id' => Category::where('name', 'Electrónica')->first()?->id ?? $categories->random()->id,
            ]
        );

        $this->command->info('100 productos creados correctamente.');
    }
}