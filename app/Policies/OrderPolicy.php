<?php

namespace App\Policies;

use App\Models\Order;
use App\Models\User;

class OrderPolicy
{
    // ¿Puede ver su historial de pedidos?
    public function viewAny(User $user): bool
    {
        return true; // todo usuario autenticado puede ver sus pedidos
    }

    // ¿Puede ver este pedido específico?
    public function view(User $user, Order $order): bool
    {
        return $user->id === $order->user_id || $user->isAdmin();
    }

    // ¿Puede crear pedidos?
    public function create(User $user): bool
    {
        return true; // todo usuario autenticado puede crear pedidos
    }

    // ¿Puede cancelar este pedido?
    public function cancel(User $user, Order $order): bool
    {
        // Solo el dueño puede cancelar, y solo si está pendiente
        return $user->id === $order->user_id && $order->isPending();
    }

    // ¿Puede cambiar el estado del pedido? (solo admin)
    public function updateStatus(User $user): bool
    {
        return $user->isAdmin();
    }

    // ¿Puede ver todos los pedidos? (solo admin)
    public function viewAll(User $user): bool
    {
        return $user->isAdmin();
    }
}
