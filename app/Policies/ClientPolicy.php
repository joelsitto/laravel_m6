<?php

namespace App\Policies;

use App\Models\Client;
use App\Models\User;

class ClientPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('ADMIN', 'GESTOR');
    }

    public function view(User $user, Client $client): bool
    {
        if ($user->hasRole('ADMIN', 'GESTOR')) {
            return true;
        }

        return $user->hasRole('CLIENT') && (int) $user->client_id === (int) $client->id;
    }

    public function create(User $user): bool
    {
        return $user->hasRole('ADMIN', 'GESTOR');
    }

    public function update(User $user, Client $client): bool
    {
        return $user->hasRole('ADMIN', 'GESTOR');
    }

    public function delete(User $user, Client $client): bool
    {
        return $user->hasRole('ADMIN', 'GESTOR');
    }
}

