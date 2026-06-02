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
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'phone' => $this->faker->optional()->phoneNumber(),
            'whatsapp' => $this->faker->optional()->phoneNumber(),
            'address' => $this->faker->optional()->address(),
            'company_name' => $this->faker->optional()->company(),
            'user_type' => $this->faker->randomElement(['final', 'mayorista']),
            'access_code' => $this->faker->optional()->numerify('######'), // 6 dígitos
            'rank_id' => Rank::factory(),
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
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
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function finalCustomer()
    {
        return $this->state(function (array $attributes) {
            return [
                'user_type' => 'final',
                'company_name' => null,
            ];
        });
    }

    /**
     * Indicate that the user is a wholesaler.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function wholesaler()
    {
        return $this->state(function (array $attributes) {
            return [
                'user_type' => 'mayorista',
                'company_name' => $this->faker->company(),
            ];
        });
    }

    /**
     * Assign a specific rank to the user.
     *
     * @param string $rankName
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function withRank($rankName)
    {
        return $this->state(function (array $attributes) use ($rankName) {
            $rank = Rank::where('name', $rankName)->first();
            return [
                'rank_id' => $rank ? $rank->id : Rank::factory(),
            ];
        });
    }

    /**
     * User with access code.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function withAccessCode()
    {
        return $this->state(function (array $attributes) {
            return [
                'access_code' => $this->faker->unique()->numerify('######'),
            ];
        });
    }
}