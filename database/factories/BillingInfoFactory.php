<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BillingInfo>
 */
class BillingInfoFactory extends Factory
{
    public function definition()
    {
        return [
            'user_id'      => User::factory(),
            'address'      => $this->faker->address(),
            'company_name' => $this->faker->optional(0.5)->company(),
            'nit'          => $this->faker->optional(0.5)->numerify('#######'),
            'business_name'=> $this->faker->optional(0.5)->company(),
            'whatsapp'     => $this->faker->optional(0.7)->numerify('591#######'),
            'is_default'   => false,
        ];
    }
}
