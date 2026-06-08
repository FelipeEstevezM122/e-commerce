<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OrderItem>
 */
class OrderItemFactory extends Factory
{
    public function definition()
    {
        // Usa un producto existente en BD (ProductFactory fue eliminado)
        $product = Product::inRandomOrder()->first();

        return [
            'order_id'           => Order::factory(),
            'product_id'         => $product?->id,
            'quantity'           => $this->faker->numberBetween(1, 10),
            'price_when_ordered' => $product?->base_price ?? $this->faker->randomFloat(2, 50, 2000),
        ];
    }
}
