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
            // Tomar órdenes pagadas o entregadas del usuario
            $orders = Order::where('user_id', $user->id)
                ->whereIn('status', ['paid', 'delivered'])
                ->get();

            if ($orders->isEmpty()) continue;

            // Agrupar por mes y acumular totales
            $grouped = $orders->groupBy(function ($order) {
                return Carbon::parse($order->created_at)->startOfMonth()->toDateString();
            });

            foreach ($grouped as $monthDate => $monthOrders) {
                $date    = Carbon::parse($monthDate);
                $total   = $monthOrders->sum('total');

                AccumulatedPurchase::updateOrCreate(
                    [
                        'user_id' => $user->id,
                        'month'   => $date->startOfMonth()->toDateString(),
                    ],
                    [
                        'quarter'         => $date->startOfQuarter()->toDateString(),
                        'year'            => $date->startOfYear()->toDateString(),
                        'total_purchases' => $total,
                    ]
                );
            }
        }

        $this->command->info('Compras acumuladas generadas correctamente.');
    }
}
