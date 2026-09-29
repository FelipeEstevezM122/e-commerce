<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Order;
use App\Models\AccumulatedPurchase;
use Carbon\Carbon;

class AccumulatedPurchaseSeeder extends Seeder
{
    public function run()
    {
        $users = User::all();

        if ($users->isEmpty()) {
            $this->command->info('Primero crea usuarios.');
            return;
        }

        foreach ($users as $user) {
            $orders = Order::where('user_id', $user->id)
                ->whereIn('status', ['paid', 'delivered'])
                ->get();

            if ($orders->isEmpty()) continue;

            // Agrupar por mes y acumular
            $grouped = $orders->groupBy(fn($o) =>
                Carbon::parse($o->created_at)->startOfMonth()->toDateString()
            );

            foreach ($grouped as $monthDate => $monthOrders) {
                AccumulatedPurchase::updateOrCreate(
                    [
                        'user_id' => $user->id,
                        'month'   => $monthDate,
                        // ELIMINADO: quarter y year → son accessors calculados
                    ],
                    [
                        'total_purchases' => $monthOrders->sum('total'),
                    ]
                );
            }
        }

        $this->command->info('Compras acumuladas generadas.');
    }
}
