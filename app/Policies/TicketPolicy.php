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
        // El ticket pertenece al usuario dueño del pedido, o es admin
        return $user->id === $ticket->order->user_id || $user->isAdmin();
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
