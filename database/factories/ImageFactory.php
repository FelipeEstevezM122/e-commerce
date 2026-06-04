<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Image>
 */
class ImageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Lista de imágenes de ejemplo de Cloudinary (imágenes reales de muestra)
        $sampleImages = [
            'samples/cloudinary/cloudinary-logo',
            'samples/cloudinary/cloudinary-icon',
            'samples/people/boy-snow',
            'samples/people/girl-face',
            'samples/people/woman-nature',
            'samples/landscapes/beach-boat',
            'samples/landscapes/mountain-road',
            'samples/landscapes/nature-path',
            'samples/food/spaghetti',
            'samples/food/ice-cream',
            'samples/food/pizza',
            'samples/animals/cat',
            'samples/animals/dog',
            'samples/animals/birds',
            'samples/bike',
            'samples/balloon',
            'samples/chair',
            'samples/coffee-cup',
            'samples/laptop',
            'samples/shoes'
        ];
        
        // O generar public_id aleatorio con formato
        $publicId = $this->faker->randomElement($sampleImages) . '/' . $this->faker->uuid();
        
        // O generar public_id completamente aleatorio
        // $publicId = 'casatek/' . $this->faker->bothify('??????_####');
        
        $formats = ['jpg', 'png', 'webp', 'jpeg'];
        $format = $this->faker->randomElement($formats);
        
        return [
            'public_id' => $publicId,
            'cloudinary_url' => 'https://res.cloudinary.com/duzht7zvr/image/upload/v' . 
                               $this->faker->unixTime() . '/' . $publicId . '.' . $format,
            'original_name' => $this->faker->word() . '_' . $this->faker->numberBetween(1, 999) . '.' . $format,
            'size' => $this->faker->numberBetween(50000, 5000000), // 50KB a 5MB
            'format' => $format,
            'created_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'updated_at' => now(),
        ];
    }
    
    /**
     * Estado: Imagen de perfil
     */
    public function profile(): static
    {
        return $this->state(fn (array $attributes) => [
            'public_id' => 'profiles/' . $this->faker->uuid(),
            'cloudinary_url' => 'https://res.cloudinary.com/duzht7zvr/image/upload/v' . 
                               $this->faker->unixTime() . '/profiles/' . $this->faker->uuid() . '.jpg',
            'format' => 'jpg',
        ]);
    }
    
    /**
     * Estado: Imagen de producto
     */
    public function product(): static
    {
        return $this->state(fn (array $attributes) => [
            'public_id' => 'products/' . $this->faker->uuid(),
            'cloudinary_url' => 'https://res.cloudinary.com/duzht7zvr/image/upload/v' . 
                               $this->faker->unixTime() . '/products/' . $this->faker->uuid() . '.webp',
            'format' => 'webp',
            'size' => $this->faker->numberBetween(100000, 2000000),
        ]);
    }
    
    /**
     * Estado: Imagen de galería
     */
    public function gallery(): static
    {
        return $this->state(fn (array $attributes) => [
            'public_id' => 'gallery/' . $this->faker->uuid(),
            'cloudinary_url' => 'https://res.cloudinary.com/duzht7zvr/image/upload/v' . 
                               $this->faker->unixTime() . '/gallery/' . $this->faker->uuid() . '.png',
            'format' => 'png',
        ]);
    }
}