<?php

namespace App\Policies;

use App\Models\Rank;
use App\Models\User;

class RankPolicy
{
    // ¿Puede ver la lista de rangos? (público, cualquiera)
    public function viewAny(?User $user): bool
    {
        return true; // el ? hace que funcione sin autenticación también
    }

    // ¿Puede ver un rango específico? (público)
    public function view(?User $user, Rank $rank): bool
    {
        return true;
    }

    // ¿Puede crear rangos? (solo admin)
    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    // ¿Puede editar rangos? (solo admin)
    public function update(User $user, Rank $rank): bool
    {
        return $user->isAdmin();
    }

    // ¿Puede eliminar rangos? (solo admin)
    public function delete(User $user, Rank $rank): bool
    {
        return $user->isAdmin();
    }
}
