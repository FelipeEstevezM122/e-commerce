<?php

namespace App\Console\Commands;

use App\Models\Order;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ExpireUnpaidReservations extends Command
{
    protected $signature = 'reservations:expire';

    protected $description = 'Cancelar reservas presenciales vencidas y devolver el stock';

    public function handle(): int
    {
        $totalCanceladas = 0;

        Order::query()
            ->where('payment_method', 'efectivo')
            ->where('status', 'pending')
            ->whereNotNull('expires_at')
            ->where('expires_at', '<=', now())
            ->select('id')
            ->chunkById(100, function ($pedidos) use (&$totalCanceladas) {

                foreach ($pedidos as $pedido) {

                    $cancelada = DB::transaction(function () use ($pedido) {

                        // Bloquear el pedido para evitar cambios simultáneos
                        $reserva = Order::whereKey($pedido->id)
                            ->lockForUpdate()
                            ->first();

                        if (!$reserva) {
                            return false;
                        }

                        // Comprobar nuevamente el estado
                        if (
                            $reserva->status !== 'pending' ||
                            $reserva->payment_method !== 'efectivo'
                        ) {
                            return false;
                        }

                        // No cancelar reservas que todavía estén vigentes
                        if (
                            !$reserva->expires_at ||
                            $reserva->expires_at->isFuture()
                        ) {
                            return false;
                        }

                        // Cancelar la reserva vencida
                        // El trigger de MySQL devuelve el stock
                        return $reserva->update([
                            'status' => 'cancelled',
                            'cancelled_at' => now(),
                            'cancellation_reason' => 'Reserva vencida por falta de pago'
                        ]);

                    }, 3);

                    if ($cancelada) {
                        $totalCanceladas++;
                    }
                }
            });

        $this->info(
            "Reservas canceladas: {$totalCanceladas}"
        );

        return self::SUCCESS;
    }
}
