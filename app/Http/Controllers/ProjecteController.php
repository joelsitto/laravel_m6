<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Projecte;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjecteController extends Controller
{
    public function index()
    {
        $projectes = Projecte::with(['client', 'gestor'])->paginate(15);

        return view('projectes.index', compact('projectes'));
    }

    public function create()
    {
        $clients = Client::where('actiu', true)->get();

        return view('projectes.Form', compact('clients'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'client_id'                  => 'required|exists:clients,id',
            'nom'                        => 'required|string|max:255',
            'descripcio'                 => 'nullable|string',
            'pressupost_hores_estimades' => 'required|integer|min:1',
            'data_inici'                 => 'nullable|date',
            'data_fi_prevista'           => 'nullable|date|after_or_equal:data_inici',
        ]);

        $client = Client::find($request->client_id);
        if (!$client->actiu) {
            return back()->withErrors(['client_id' => 'El client seleccionat no està actiu.']);
        }

        $projecte = Projecte::create([
            'client_id'                  => $request->client_id,
            'gestor_id'                  => Auth::id(),
            'nom'                        => $request->nom,
            'descripcio'                 => $request->descripcio,
            'codi_projecte'              => 'TEMP',
            'estat'                      => 'PLANIFICACIO',
            'data_inici'                 => $request->data_inici,
            'data_fi_prevista'           => $request->data_fi_prevista,
            'pressupost_hores_estimades' => $request->pressupost_hores_estimades,
        ]);

        $projecte->codi_projecte = 'PROJ-' . now()->year . '-' . str_pad($projecte->id, 3, '0', STR_PAD_LEFT);
        $projecte->save();

        return redirect()->route('projectes.show', $projecte)->with('success', 'Projecte creat correctament.');
    }

    public function show(Projecte $projecte)
    {
        $projecte->load(['client', 'gestor', 'desenvolupadors']);

        return view('projectes.show', compact('projecte'));
    }

    public function edit(Projecte $projecte)
    {
        return view('projectes.Form', compact('projecte'));
    }

    public function update(Request $request, Projecte $projecte)
    {
        $request->validate([
            'nom'                        => ['required', 'string', 'max:255'],
            'descripcio'                 => ['nullable', 'string'],
            'pressupost_hores_estimades' => ['required', 'integer', 'min:1'],
            'data_inici'                 => ['nullable', 'date'],
            'data_fi_prevista'           => ['nullable', 'date', 'after_or_equal:data_inici'],
        ]);

        $projecte->update([
            'nom'                        => $request->nom,
            'descripcio'                 => $request->descripcio,
            'pressupost_hores_estimades' => $request->pressupost_hores_estimades,
            'data_inici'                 => $request->data_inici,
            'data_fi_prevista'           => $request->data_fi_prevista,
        ]);

        return redirect()->route('projectes.show', $projecte)
            ->with('success', 'Projecte actualitzat correctament.');
    }

    public function canviarEstat(Request $request, Projecte $projecte)
    {
        $request->validate([
            'estat' => ['required', 'in:PLANIFICACIO,EN_CURS,PAUSAT,FINALITZAT,CANCELAT'],
        ]);

        $estatActual = $projecte->estat;
        $nouEstat    = $request->estat;

        $transicionsValides = [
            'PLANIFICACIO' => ['EN_CURS', 'CANCELAT'],
            'EN_CURS'      => ['PAUSAT', 'FINALITZAT', 'CANCELAT'],
            'PAUSAT'       => ['EN_CURS', 'CANCELAT'],
            'FINALITZAT'   => [],
            'CANCELAT'     => [],
        ];

        if (!in_array($nouEstat, $transicionsValides[$estatActual])) {
            return redirect()->route('projectes.show', $projecte)
                ->with('error', "No es pot passar de {$estatActual} a {$nouEstat}.");
        }

        if ($nouEstat === 'EN_CURS' && !$projecte->data_inici) {
            return redirect()->route('projectes.show', $projecte)
                ->with('error', 'Cal definir una data d\'inici abans de posar el projecte EN_CURS.');
        }

        $dades = ['estat' => $nouEstat];

        if ($nouEstat === 'EN_CURS' && $estatActual === 'PLANIFICACIO') {
            $dades['data_inici'] = $dades['data_inici'] ?? now()->toDateString();
        }

        if ($nouEstat === 'FINALITZAT') {
            $dades['data_fi_real'] = now()->toDateString();
        }

        $projecte->update($dades);

        return redirect()->route('projectes.show', $projecte)
            ->with('success', "Estat canviat a {$nouEstat} correctament.");
    }
}
