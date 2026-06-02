<?php

namespace Database\Factories;

use App\Models\Brand;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => $this->faker->unique()->words(3, true),
            'description' => $this->faker->optional()->paragraph(),
            'base_price' => $this->faker->randomFloat(2, 10, 1000),
            'stock' => $this->faker->numberBetween(0, 100),
            'image' => $this->faker->optional()->imageUrl(500, 500, 'products'),
            'sku' => $this->faker->unique()->bothify('SKU-#####-???'),
            'warranty_days' => $this->faker->optional()->randomElement([0, 30, 90, 180, 365, 730]),
            'brand_id' => Brand::factory(),
            'category_id' => Category::factory(),
        ];
    }
}