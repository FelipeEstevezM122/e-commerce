<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AccumulatedPurchase>
 */
class AccumulatedPurchaseFactory extends Factory
{
    public function definition()
    {
        $date = $this->faker->dateTimeBetween('-12 months', 'now');
        $month   = \Carbon\Carbon::instance($date)->startOfMonth()->toDateString();
        $quarter = \Carbon\Carbon::instance($date)->startOfQuarter()->toDateString();
        $year    = \Carbon\Carbon::instance($date)->startOfYear()->toDateString();

        return [
            'user_id'          => User::factory(),
            'month'            => $month,
            'quarter'          => $quarter,
            'year'             => $year,
            'total_purchases'  => $this->faker->randomFloat(2, 0, 15000),
        ];
    }
}
