<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run()
    {
        // Categorías principales específicas
        $mainCategories = [
            'Electrónica' => 'Dispositivos electrónicos y accesorios',
            'Ropa' => 'Prendas de vestir para toda la familia',
            'Hogar' => 'Artículos para el hogar y decoración',
            'Deportes' => 'Equipamiento deportivo',
            'Libros' => 'Libros y material educativo',
        ];
        
        foreach ($mainCategories as $name => $description) {
            Category::firstOrCreate(
                ['name' => $name],
                ['description' => $description]
            );
        }
        
        // 20 categorías adicionales aleatorias
        Category::factory(20)->create();
    }
}