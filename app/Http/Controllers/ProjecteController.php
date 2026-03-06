<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Projecte;
use App\Models\User;
use Illuminate\Http\Request;

class ProjecteController extends Controller
{
    public function index()
    {
        $projectes = Projecte::with(['client', 'gestor'])->paginate(15);

        return view('projectes.index', compact('projectes'));
    }

    public function create()
    {
        $clients = Client::all();
        $gestors = User::whereIn('rol', ['GESTOR', 'ADMIN'])->get();

        return view('projectes.Form', compact('clients', 'gestors'));
    }

    public function store(Request $request)
    {
        $projecte = Projecte::create([
            'client_id'                  => $request->client_id,
            'gestor_id'                  => $request->gestor_id,
            'nom'                        => $request->nom,
            'descripcio'                 => $request->descripcio,
            'codi_projecte'              => 'TEMP',
            'estat'                      => 'PLANIFICACIO',
            'data_inici'                 => $request->data_inici,
            'data_fi_prevista'           => $request->data_fi_prevista,
            'pressupost_hores_previstes' => $request->pressupost_hores_previstes,
        ]);

        $projecte->codi_projecte = 'PROJ-' . now()->year . '-' . str_pad($projecte->id, 3, '0', STR_PAD_LEFT);
        $projecte->save();

        return redirect()->route('projectes.show', $projecte)
            ->with('success', 'Projecte creat correctament.');
    }

    public function show(Projecte $projecte)
    {
        $projecte->load(['client', 'gestor', 'desenvolupadors']);

        return view('projectes.show', compact('projecte'));
    }

    public function edit(Projecte $projecte)
    {
        $gestors = User::whereIn('rol', ['GESTOR', 'ADMIN'])->get();

        return view('projectes.Form', compact('projecte', 'gestors'));
    }

    public function update(Request $request, Projecte $projecte)
    {
        $projecte->update([
            'nom'                        => $request->nom,
            'descripcio'                 => $request->descripcio,
            'gestor_id'                  => $request->gestor_id,
            'estat'                      => $request->estat ?? $projecte->estat,
            'pressupost_hores_previstes' => $request->pressupost_hores_previstes,
            'data_inici'                 => $request->data_inici,
            'data_fi_prevista'           => $request->data_fi_prevista,
        ]);

        return redirect()->route('projectes.show', $projecte)
            ->with('success', 'Projecte actualitzat correctament.');
    }

    public function canviarEstat(Request $request, Projecte $projecte)
    {
        $projecte->update(['estat' => $request->estat]);

        return redirect()->route('projectes.show', $projecte)
            ->with('success', 'Estat canviat correctament.');
    }
}
