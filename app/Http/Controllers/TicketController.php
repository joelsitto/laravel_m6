<?php

namespace App\Http\Controllers;

use App\Http\Requests\AssignarTicketRequest;
use App\Http\Requests\CanviarEstatTicketRequest;
use App\Http\Requests\StoreTicketRequest;
use App\Http\Requests\UpdateTicketRequest;
use App\Models\Projecte;
use App\Models\Ticket;
use App\Models\User;

class TicketController extends Controller
{
    private const TRANSICIONS = [
        'NOU' => ['ASSIGNAT'],
        'ASSIGNAT' => ['EN_PROGRES'],
        'EN_PROGRES' => ['EN_REVISIO'],
        'EN_REVISIO' => ['TANCAT'],
        'TANCAT' => [],
    ];

    public function index(Projecte $projecte)
    {
        $this->authorize('viewAny', Ticket::class);
        $this->authorize('view', $projecte);

        $tickets = $projecte->tickets()
            ->with(['creador', 'assignat'])
            ->withCount('comentaris')
            ->latest()
            ->paginate(15);

        return view('tickets.index', compact('projecte', 'tickets'));
    }

    public function create(Projecte $projecte)
    {
        $this->authorize('create', [Ticket::class, $projecte]);

        $ticketsPareDisponibles = $projecte->tickets()
            ->whereNull('ticket_pare_id')
            ->orderBy('codi_ticket')
            ->get();

        return view('tickets.create', compact('projecte', 'ticketsPareDisponibles'));
    }

    public function store(StoreTicketRequest $request, Projecte $projecte)
    {
        $this->authorize('create', [Ticket::class, $projecte]);

        $validated = $request->validated();

        if ((int) $validated['projecte_id'] !== $projecte->id) {
            abort(404);
        }

        $ticket = Ticket::create([
            'projecte_id' => $projecte->id,
            'creador_id' => $request->user()->id,
            'codi_ticket' => 'TMP-' . uniqid(),
            'titol' => $validated['titol'],
            'descripcio' => $validated['descripcio'] ?? null,
            'ticket_pare_id' => $validated['ticket_pare_id'] ?? null,
            'estat' => 'NOU',
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
        $this->authorize('view', $ticket);

        $ticket->load(['creador', 'assignat', 'pare', 'fills', 'comentaris.autor', 'registresTemps.user'])
            ->loadSum('registresTemps', 'hores');
        $devsEquip = $projecte->desenvolupadors()->orderBy('name')->get();
        $transicionsPossibles = $this->transicionsDisponibles(auth()->user(), $ticket);

        return view('tickets.show', compact('projecte', 'ticket', 'devsEquip', 'transicionsPossibles'));
    }

    public function edit(Projecte $projecte, Ticket $ticket)
    {
        $this->assertTicketBelongsToProjecte($projecte, $ticket);
        $this->authorize('update', $ticket);

        $ticketsPareDisponibles = $projecte->tickets()
            ->whereNull('ticket_pare_id')
            ->where('id', '!=', $ticket->id)
            ->orderBy('codi_ticket')
            ->get();

        return view('tickets.edit', compact('projecte', 'ticket', 'ticketsPareDisponibles'));
    }

    public function update(UpdateTicketRequest $request, Projecte $projecte, Ticket $ticket)
    {
        $this->assertTicketBelongsToProjecte($projecte, $ticket);
        $this->authorize('update', $ticket);

        $validated = $request->validated();

        $ticket->update([
            'titol' => $validated['titol'],
            'descripcio' => $validated['descripcio'] ?? null,
            'ticket_pare_id' => $validated['ticket_pare_id'] ?? null,
        ]);

        return redirect()->route('projectes.tickets.show', [$projecte, $ticket])
            ->with('success', 'Actualitzat');
    }

    public function assignar(AssignarTicketRequest $request, Projecte $projecte, Ticket $ticket)
    {
        $this->assertTicketBelongsToProjecte($projecte, $ticket);
        $this->authorize('assignar', $ticket);

        $validated = $request->validated();

        if ($ticket->estat !== 'NOU') {
            return redirect()->back()->withErrors([
                'assignat_a' => 'Per assignar un dev, el ticket ha d\'estar en estat NOU.',
            ]);
        }

        $ticket->update([
            'assignat_a' => (int) $validated['assignat_a'],
            'estat' => 'ASSIGNAT',
        ]);

        return redirect()->route('projectes.tickets.show', [$projecte, $ticket])
            ->with('success', 'Ticket assignat correctament.');
    }

    public function canviarEstat(CanviarEstatTicketRequest $request, Ticket $ticket)
    {
        $this->authorize('canviarEstat', $ticket);

        $estatNou = $request->validated('estat');
        $user = $request->user();

        if ($estatNou === 'TANCAT' && $ticket->fills()->where('estat', '!=', 'TANCAT')->exists()) {
            $message = 'No pots tancar el ticket pare mentre hi hagi fills no tancats.';

            if ($request->expectsJson()) {
                return response()->json(['message' => $message], 422);
            }

            return redirect()->back()->withErrors(['estat' => $message]);
        }

        if (! $this->potCanviarAEstat($user, $ticket, $estatNou)) {
            $message = 'Transicio no valida. No es pot canviar l\'estat amb aquest usuari o saltant passos.';

            if ($request->expectsJson()) {
                return response()->json(['message' => $message], 422);
            }

            return redirect()->back()->withErrors(['estat' => $message]);
        }

        $ticket->update(['estat' => $estatNou]);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Estat actualitzat correctament.',
                'estat' => $ticket->estat,
            ]);
        }

        return redirect()->back()->with('success', 'Estat actualitzat correctament.');
    }

    private function assertTicketBelongsToProjecte(Projecte $projecte, Ticket $ticket): void
    {
        abort_if($ticket->projecte_id !== $projecte->id, 404);
    }

    private function transicionsDisponibles(User $user, Ticket $ticket): array
    {
        $possibles = self::TRANSICIONS[$ticket->estat] ?? [];
        $resultat = [];

        foreach ($possibles as $estatNou) {
            if ($this->potCanviarAEstat($user, $ticket, $estatNou)) {
                $resultat[] = $estatNou;
            }
        }

        return $resultat;
    }

    private function potCanviarAEstat(User $user, Ticket $ticket, string $estatNou): bool
    {
        $estatActual = (string) $ticket->estat;
        $seguents = self::TRANSICIONS[$estatActual] ?? [];

        if (! in_array($estatNou, $seguents, true)) {
            return false;
        }

        if ($estatActual === 'ASSIGNAT' && $estatNou === 'EN_PROGRES') {
            return $user->hasRole('DEV') && (int) $ticket->assignat_a === (int) $user->id;
        }

        if ($estatActual === 'EN_PROGRES' && $estatNou === 'EN_REVISIO') {
            return $user->hasRole('DEV') && (int) $ticket->assignat_a === (int) $user->id;
        }

        if ($estatActual === 'EN_REVISIO' && $estatNou === 'TANCAT') {
            if ($ticket->fills()->where('estat', '!=', 'TANCAT')->exists()) {
                return false;
            }

            return $user->hasRole('GESTOR', 'ADMIN');
        }

        // NOU -> ASSIGNAT es fa amb l'accio assignar
        return false;
    }
}

