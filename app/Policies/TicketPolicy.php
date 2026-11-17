<?php

namespace App\Policies;

use App\Models\Ticket;
use App\Models\User;

class TicketPolicy
{
    // ¿Puede ver su lista de tickets?
    public function viewAny(User $user): bool
    {
        return true; // todo usuario autenticado puede ver los suyos
    }

    // ¿Puede ver este ticket específico?
    
    public function view(User $user, Ticket $ticket): bool
    {
        $order = $ticket->order;

        // Solo el propietario o un administrador puede consultar el ticket
        if (!$order || ($user->id !== $order->user_id && !$user->isAdmin())) {
            return false;
        }

        // Permitir tickets de pedidos pagados, enviados y entregados
        if (in_array($order->status, ['paid', 'shipped', 'delivered'], true)) {
            return true;
        }

        // Permitir comprobantes de reservas presenciales vigentes
        if (
            $order->status === 'pending' &&
            $order->payment_method === 'efectivo' &&
            $order->expires_at !== null &&
            now()->lt($order->expires_at)
        ) {
            return true;
        }

        // Bloquear reservas vencidas y pedidos cancelados
        return false;
    }

    // ¿Puede ver todos los tickets? (solo admin)
    public function viewAll(User $user): bool
    {
        return $user->isAdmin();
    }

    // ¿Puede generar un ticket manualmente? (solo admin)
    public function generate(User $user): bool
    {
        return $user->isAdmin();
    }
}
