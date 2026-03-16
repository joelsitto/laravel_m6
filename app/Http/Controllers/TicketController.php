<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTicketRequest;
use App\Http\Requests\UpdateTicketRequest;
use App\Models\Projecte;
use App\Models\Ticket;

class TicketController extends Controller
{
    public function index(Projecte $projecte)
    {
        $tickets = $projecte->tickets()
            ->with(['creador'])
            ->withCount('comentaris')
            ->latest()
            ->paginate(15);

        return view('tickets.index', compact('projecte', 'tickets'));
    }

    public function create(Projecte $projecte)
    {
        return view('tickets.create', compact('projecte'));
    }

    public function store(StoreTicketRequest $request, Projecte $projecte)
    {
        $validated = $request->validated();

        $ticket = Ticket::create([
            'projecte_id' => $projecte->id,
            'creador_id' => $request->user()->id,
            'codi_ticket' => 'TMP-' . uniqid(),
            'titol' => $validated['titol'],
            'descripcio' => $validated['descripcio'] ?? null,
            'estat' => $validated['estat'] ?? 'NOU',
        ]);

        $ticket->update([
            'codi_ticket' => 'TKT-' . now()->year . '-' . str_pad((string) $ticket->id, 4, '0', STR_PAD_LEFT),
        ]);

        return redirect()->route('projectes.tickets.show', [$projecte, $ticket])
            ->with('success', 'Creat');
    }

    public function show(Projecte $projecte, Ticket $ticket)
    {
        $this->assertTicketBelongsToProjecte($projecte, $ticket);

        $ticket->load(['creador', 'comentaris.autor']);

        return view('tickets.show', compact('projecte', 'ticket'));
    }

    public function edit(Projecte $projecte, Ticket $ticket)
    {
        $this->assertTicketBelongsToProjecte($projecte, $ticket);

        return view('tickets.edit', compact('projecte', 'ticket'));
    }

    public function update(UpdateTicketRequest $request, Projecte $projecte, Ticket $ticket)
    {
        $this->assertTicketBelongsToProjecte($projecte, $ticket);

        $validated = $request->validated();

        $ticket->update([
            'titol' => $validated['titol'],
            'descripcio' => $validated['descripcio'] ?? null,
            'estat' => $validated['estat'],
        ]);

        return redirect()->route('projectes.tickets.show', [$projecte, $ticket])
            ->with('success', 'Actualitzat');
    }

    private function assertTicketBelongsToProjecte(Projecte $projecte, Ticket $ticket): void
    {
        abort_if($ticket->projecte_id !== $projecte->id, 404);
    }
}

