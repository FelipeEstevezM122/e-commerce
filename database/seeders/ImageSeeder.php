<?php

namespace Database\Seeders;

use App\Models\Image;
use Illuminate\Database\Seeder;

class ImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Opción 1: Crear 50 imágenes aleatorias
        Image::factory()
            ->count(50)
            ->create();
        
        // Opción 2: Crear tipos específicos
        // Image::factory()
        //     ->count(10)
        //     ->profile()
        //     ->create();
        
        // Image::factory()
        //     ->count(20)
        //     ->product()
        //     ->create();
        
        // Image::factory()
        //     ->count(30)
        //     ->gallery()
        //     ->create();
        
        // Opción 3: Crear imágenes específicas manualmente
        // $this->createSpecificImages();
    }
    
    /**
     * Crear imágenes específicas manualmente
     */
    private function createSpecificImages(): void
    {
        $specificImages = [

            [
                'public_id' => 'casatek/banner-principal',
                'cloudinary_url' => 'https://res.cloudinary.com/duzht7zvr/image/upload/q_auto/f_auto/v1780604861/waifu_a5zgml.jpg',
                'original_name' => 'banner_principal.jpg',
                'size' => 500000,
                'format' => 'jpg',
            ],

        ];
        
        foreach ($specificImages as $image) {
            Image::create($image);
        }
    }
}