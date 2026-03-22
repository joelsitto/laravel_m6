<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreClientRequest;
use App\Http\Requests\UpdateClientRequest;
use App\Models\Client;

class ClientController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', Client::class);

        $clients = Client::withCount('projectes')->paginate(15);

        return view('clients.index', compact('clients'));
    }

    public function create()
    {
        $this->authorize('create', Client::class);

        return view('clients.Form');
    }

    public function store(StoreClientRequest $request)
    {
        $this->authorize('create', Client::class);

        Client::create($request->validated());

        return redirect()->route('clients.index')
            ->with('success', 'Creat');
    }

    public function show(Client $client)
    {
        $this->authorize('view', $client);

        $client->load('projectes');

        return view('clients.show', compact('client'));
    }

    public function edit(Client $client)
    {
        $this->authorize('update', $client);

        return view('clients.Form', compact('client'));
    }

    public function update(UpdateClientRequest $request, Client $client)
    {
        $this->authorize('update', $client);

        $validated = $request->validated();

        $client->update([
            'nom'            => $validated['nom'],
            'cif'            => $validated['cif'],
            'email_contacte' => $validated['email_contacte'] ?? $client->email_contacte,
            'telefon'        => $validated['telefon'] ?? $client->telefon,
            'direccio'       => $validated['direccio'] ?? $client->direccio,
            'actiu'          => (bool) ($validated['actiu'] ?? $client->actiu),
        ]);

        return redirect()->route('clients.show', $client)
            ->with('success', 'Actualitzat');
    }

}
