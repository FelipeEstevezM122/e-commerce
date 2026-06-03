<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    public function definition()
    {
        return [
            'user_id'            => User::inRandomOrder()->first()?->id ?? User::factory(),
            'order_number'       => 'ORD-' . strtoupper(Str::random(8)) . '-' . $this->faker->unique()->numerify('####'),
            'total'              => $this->faker->randomFloat(2, 10, 1000),
            'status'             => $this->faker->randomElement(['pending', 'paid', 'shipped', 'delivered', 'cancelled']),
            'payment_method'     => $this->faker->optional()->randomElement(['credit_card', 'debit_card', 'paypal', 'bank_transfer', 'cash']),
            'customer_whatsapp'  => $this->faker->optional()->phoneNumber(),
            'nit'                => $this->faker->optional()->numerify('#######-#'),
            'business_name'      => $this->faker->optional()->company(),
            'shipping_address'   => $this->faker->address(),
        ];
    }
}
