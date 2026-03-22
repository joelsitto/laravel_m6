<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProjecteRequest;
use App\Http\Requests\UpdateProjecteRequest;
use App\Models\Client;
use App\Models\Projecte;
use App\Models\User;
use Illuminate\Http\Request;

class ProjecteController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', Projecte::class);

        $user = auth()->user();

        $query = Projecte::query()->with(['client', 'gestor']);

        if ($user->hasRole('CLIENT')) {
            $query->where('client_id', $user->client_id);
        } elseif ($user->hasRole('DEV')) {
            $idsProjectesDev = [];
            foreach ($user->projectes as $projecteDev) {
                $idsProjectesDev[] = $projecteDev->id;
            }

            $query->whereIn('id', $idsProjectesDev);
        }

        $projectes = $query->paginate(15);

        return view('projectes.index', compact('projectes'));
    }

    public function create()
    {
        $this->authorize('create', Projecte::class);

        $clients = Client::all();
        $gestors = User::whereIn('rol', ['GESTOR', 'ADMIN'])->get();

        return view('projectes.Form', compact('clients', 'gestors'));
    }

    public function store(StoreProjecteRequest $request)
    {
        $this->authorize('create', Projecte::class);

        $validated = $request->validated();

        $projecte = Projecte::create([
            'client_id'                  => $validated['client_id'],
            'gestor_id'                  => $validated['gestor_id'],
            'nom'                        => $validated['nom'],
            'descripcio'                 => $validated['descripcio'] ?? null,
            'codi_projecte'              => 'TEMP',
            'estat'                      => 'PLANIFICACIO',
            'data_inici'                 => $validated['data_inici'] ?? null,
            'data_fi_prevista'           => $validated['data_fi_prevista'] ?? null,
            'pressupost_hores_previstes' => $validated['pressupost_hores_previstes'],
        ]);

        $projecte->codi_projecte = 'PROJ-' . now()->year . '-' . str_pad($projecte->id, 3, '0', STR_PAD_LEFT);
        $projecte->save();

        $projecte->configuracio()->create();

        return redirect()->route('projectes.show', $projecte)
            ->with('success', 'Creat');
    }

    public function show(Projecte $projecte)
    {
        $this->authorize('view', $projecte);

        $projecte->load(['client', 'gestor', 'desenvolupadors']);

        return view('projectes.show', compact('projecte'));
    }

    public function edit(Projecte $projecte)
    {
        $this->authorize('update', $projecte);

        $gestors = User::whereIn('rol', ['GESTOR', 'ADMIN'])->get();

        return view('projectes.Form', compact('projecte', 'gestors'));
    }

    public function update(UpdateProjecteRequest $request, Projecte $projecte)
    {
        $this->authorize('update', $projecte);

        $validated = $request->validated();

        $projecte->update([
            'nom'                        => $validated['nom'],
            'descripcio'                 => $validated['descripcio'] ?? null,
            'gestor_id'                  => $validated['gestor_id'],
            'estat'                      => $validated['estat'] ?? $projecte->estat,
            'pressupost_hores_previstes' => $validated['pressupost_hores_previstes'],
            'data_inici'                 => $validated['data_inici'] ?? null,
            'data_fi_prevista'           => $validated['data_fi_prevista'] ?? null,
        ]);

        return redirect()->route('projectes.show', $projecte)
            ->with('success', 'Actualitzat');
    }

    public function canviarEstat(Request $request, Projecte $projecte)
    {
        $this->authorize('update', $projecte);

        $projecte->update(['estat' => $request->estat]);

        return redirect()->route('projectes.show', $projecte)
            ->with('success', 'Estat canviat correctament.');
    }
}
