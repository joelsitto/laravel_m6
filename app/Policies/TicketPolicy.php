<?php

namespace App\Policies;

use App\Models\Projecte;
use App\Models\Ticket;
use App\Models\User;

class TicketPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('ADMIN', 'GESTOR', 'DEV', 'CLIENT');
    }

    public function view(User $user, Ticket $ticket): bool
    {
        if ($user->hasRole('ADMIN', 'GESTOR')) {
            return true;
        }

        if ($user->hasRole('DEV')) {
            return $ticket->projecte->desenvolupadors()
                ->where('users.id', $user->id)
                ->exists();
        }

        if ($user->hasRole('CLIENT')) {
            return (int) $ticket->projecte->client_id === (int) $user->client_id;
        }

        return false;
    }

    public function create(User $user, Projecte $projecte): bool
    {
        if ($user->hasRole('ADMIN', 'GESTOR')) {
            return true;
        }

        if ($user->hasRole('DEV')) {
            return $projecte->desenvolupadors()
                ->where('users.id', $user->id)
                ->exists();
        }

        return false;
    }

    public function update(User $user, Ticket $ticket): bool
    {
        if ($user->hasRole('ADMIN', 'GESTOR')) {
            return true;
        }

        if ($user->hasRole('DEV')) {
            return $ticket->projecte->desenvolupadors()
                ->where('users.id', $user->id)
                ->exists();
        }

        return false;
    }

    public function delete(User $user, Ticket $ticket): bool
    {
        return $user->hasRole('ADMIN', 'GESTOR');
    }
}

