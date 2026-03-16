<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreClientRequest;
use App\Http\Requests\UpdateClientRequest;
use App\Models\Client;

class ClientController extends Controller
{
    public function index()
    {
        $clients = Client::withCount('projectes')->paginate(15);

        return view('clients.index', compact('clients'));
    }

    public function create()
    {
        return view('clients.Form');
    }

    public function store(StoreClientRequest $request)
    {
        Client::create($request->validated());

        return redirect()->route('clients.index')
            ->with('success', 'Creat');
    }

    public function show(Client $client)
    {
        $client->load('projectes');

        return view('clients.show', compact('client'));
    }

    public function edit(Client $client)
    {
        return view('clients.Form', compact('client'));
    }

    public function update(UpdateClientRequest $request, Client $client)
    {
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
