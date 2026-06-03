<?php

namespace Database\Factories;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AccumulatedPurchase>
 */
class AccumulatedPurchaseFactory extends Factory
{
    public function definition()
    {
        $month = Carbon::instance(
            $this->faker->dateTimeBetween('-12 months', 'now')
        )->startOfMonth()->toDateString();

        return [
            'user_id'         => User::factory(),
            'month'           => $month,
            // ELIMINADO: quarter → se calcula con $record->quarter (accessor)
            // ELIMINADO: year    → se calcula con $record->year (accessor)
            'total_purchases' => $this->faker->randomFloat(2, 0, 15000),
        ];
    }
}
