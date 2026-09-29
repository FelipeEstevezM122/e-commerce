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
    public function definition()
    {
        $rankId = Rank::inRandomOrder()->first()?->id ?? Rank::factory()->create()->id;

        return [
            'name'              => $this->faker->name(),
            'email'             => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password'          => bcrypt(Str::random(10)),
            'phone'             => $this->faker->optional()->numerify('591#######'),
            'whatsapp'          => $this->faker->optional()->numerify('591#######'),
            'access_code'       => $this->faker->optional()->numerify('######'),
            'rank_id'           => $rankId,
            'remember_token'    => Str::random(10),
            // ELIMINADO: user_type    → asignado via roles en UserSeeder
            // ELIMINADO: address      → en BillingInfo
            // ELIMINADO: company_name → en BillingInfo
        ];
    }

    public function unverified()
    {
        return $this->state(fn() => ['email_verified_at' => null]);
    }
}
