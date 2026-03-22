<?php

namespace App\Policies;

use App\Models\Comentari;
use App\Models\Ticket;
use App\Models\User;

class ComentariPolicy
{
    public function create(User $user, Ticket $ticket): bool
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

    public function delete(User $user, Comentari $comentari): bool
    {
        if ($user->hasRole('ADMIN', 'GESTOR')) {
            return true;
        }

        if ((int) $comentari->autor_id !== (int) $user->id) {
            return false;
        }

        if ($user->hasRole('DEV')) {
            return $comentari->ticket->projecte->desenvolupadors()
                ->where('users.id', $user->id)
                ->exists();
        }

        if ($user->hasRole('CLIENT')) {
            return (int) $comentari->ticket->projecte->client_id === (int) $user->client_id;
        }

        return false;
    }
}


