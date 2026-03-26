<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRegistreTempsRequest;
use App\Models\RegistreTemps;
use App\Models\Ticket;

class RegistreTempsController extends Controller
{
    public function store(StoreRegistreTempsRequest $request, Ticket $ticket)
    {
        $this->authorize('view', $ticket);

        $validated = $request->validated();

        RegistreTemps::create([
            'ticket_id' => $ticket->id,
            'user_id' => $request->user()->id,
            'data' => $validated['data'],
            'hores' => $validated['hores'],
            'descripcio' => $validated['descripcio'] ?? null,
        ]);

        return redirect()->back()->with('success', 'Registre de temps creat correctament.');
    }

    public function destroy(RegistreTemps $registreTemps)
    {
        $user = auth()->user();

        if ((int) $registreTemps->user_id !== (int) $user->id && ! $user->hasRole('ADMIN', 'GESTOR')) {
            abort(403);
        }

        $registreTemps->delete();

        return redirect()->back()->with('success', 'Registre de temps eliminat correctament.');
    }
}

