<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreComentariRequest;
use App\Models\Comentari;
use App\Models\Ticket;

class ComentariController extends Controller
{
    public function store(StoreComentariRequest $request, Ticket $ticket)
    {
        $validated = $request->validated();

        if ((int) $validated['ticket_id'] !== $ticket->id) {
            abort(404);
        }

        Comentari::create([
            'ticket_id' => $ticket->id,
            'autor_id' => $request->user()->id,
            'text' => $validated['text'],
        ]);

        return redirect()->route('projectes.tickets.show', [$ticket->projecte_id, $ticket])
            ->with('success', 'Creat');
    }

    public function destroy(Comentari $comentari)
    {
        $ticket = $comentari->ticket;
        $projecteId = $ticket->projecte_id;

        $comentari->delete();

        return redirect()->route('projectes.tickets.show', [$projecteId, $ticket])
            ->with('success', 'Actualitzat');
    }
}

