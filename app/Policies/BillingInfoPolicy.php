<?php

namespace App\Policies;

use App\Models\BillingInfo;
use App\Models\User;

class BillingInfoPolicy
{
    // ¿Puede listar sus propias direcciones?
    public function viewAny(User $user): bool
    {
        return true; // todo usuario autenticado puede listar las suyas
    }

    // ¿Puede ver esta dirección específica?
    public function view(User $user, BillingInfo $billingInfo): bool
    {
        return $user->id === $billingInfo->user_id || $user->isAdmin();
    }

    // ¿Puede crear una nueva dirección?
    public function create(User $user): bool
    {
        return true; // todo usuario autenticado puede agregar direcciones
    }

    // ¿Puede editar esta dirección?
    public function update(User $user, BillingInfo $billingInfo): bool
    {
        return $user->id === $billingInfo->user_id || $user->isAdmin();
    }

    // ¿Puede marcarla como predeterminada?
    public function setDefault(User $user, BillingInfo $billingInfo): bool
    {
        return $user->id === $billingInfo->user_id; // solo el dueño
    }

    // ¿Puede eliminar esta dirección?
    public function delete(User $user, BillingInfo $billingInfo): bool
    {
        return $user->id === $billingInfo->user_id || $user->isAdmin();
    }
}
