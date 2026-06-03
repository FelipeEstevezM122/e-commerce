<?php

namespace Database\Factories;

use App\Models\Rank;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        // Usa un rank existente en lugar de crear uno nuevo con factory
        $rankId = Rank::inRandomOrder()->first()?->id ?? Rank::factory()->create()->id;

        return [
            'name'               => $this->faker->name(),
            'email'              => $this->faker->unique()->safeEmail(),
            'email_verified_at'  => now(),
            'password'           => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'phone'              => $this->faker->optional()->phoneNumber(),
            'whatsapp'           => $this->faker->optional()->phoneNumber(),
            'address'            => $this->faker->optional()->address(),
            'company_name'       => $this->faker->optional()->company(),
            'user_type'          => $this->faker->randomElement(['final', 'mayorista']),
            'access_code'        => $this->faker->optional()->numerify('######'),
            'rank_id'            => $rankId,
            'remember_token'     => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified()
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }

    /**
     * Indicate that the user is a final customer.
     */
    public function finalCustomer()
    {
        return $this->state(function (array $attributes) {
            return [
                'user_type'    => 'final',
                'company_name' => null,
            ];
        });
    }

    /**
     * Indicate that the user is a wholesaler.
     */
    public function wholesaler()
    {
        return $this->state(function (array $attributes) {
            return [
                'user_type'    => 'mayorista',
                'company_name' => $this->faker->company(),
            ];
        });
    }

    /**
     * Assign a specific rank to the user.
     */
    public function withRank($rankName)
    {
        return $this->state(function (array $attributes) use ($rankName) {
            $rank = Rank::where('name', $rankName)->first();
            return [
                'rank_id' => $rank ? $rank->id : Rank::inRandomOrder()->first()?->id,
            ];
        });
    }

    /**
     * User with access code.
     */
    public function withAccessCode()
    {
        return $this->state(function (array $attributes) {
            return [
                'access_code' => $this->faker->numerify('######'),
            ];
        });
    }
}
