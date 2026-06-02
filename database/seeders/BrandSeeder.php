<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Brand;

class BrandSeeder extends Seeder
{
    public function run()
    {
        // Crear 20 marcas aleatorias
        Brand::factory(20)->create();
        
        // O crear marcas específicas
        $specificBrands = ['Nike', 'Adidas', 'Sony', 'Samsung', 'Apple'];
        foreach ($specificBrands as $brandName) {
            Brand::firstOrCreate(
                ['name' => $brandName],
                ['description' => "Marca {$brandName} de alta calidad"]
            );
        }
    }
}