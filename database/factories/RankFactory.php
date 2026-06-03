<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Rank>
 */
class RankFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => $this->faker->randomElement([
                'Bronce', 'Plata', 'Oro', 'Platino', 'Diamante', 'Premium'
            ]),
            'monthly_minimum_purchase' => $this->faker->randomElement([0, 1000, 5000, 10000, 20000, 50000]),
            'description' => $this->faker->optional()->sentence(),
        ];
    }
}
