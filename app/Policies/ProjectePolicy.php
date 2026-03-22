<?php

namespace App\Policies;

use App\Models\Projecte;
use App\Models\User;

class ProjectePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('ADMIN', 'GESTOR', 'DEV', 'CLIENT');
    }

    public function view(User $user, Projecte $projecte): bool
    {
        if ($user->hasRole('ADMIN', 'GESTOR')) {
            return true;
        }

        if ($user->hasRole('DEV')) {
            return $projecte->desenvolupadors()
                ->where('users.id', $user->id)
                ->exists();
        }

        if ($user->hasRole('CLIENT')) {
            return (int) $projecte->client_id === (int) $user->client_id;
        }

        return false;
    }

    public function create(User $user): bool
    {
        return $user->hasRole('ADMIN', 'GESTOR');
    }

    public function update(User $user, Projecte $projecte): bool
    {
        return $user->hasRole('ADMIN', 'GESTOR');
    }

    public function delete(User $user, Projecte $projecte): bool
    {
        return $user->hasRole('ADMIN', 'GESTOR');
    }
}

