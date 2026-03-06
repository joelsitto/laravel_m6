<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;

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

    public function store(Request $request)
    {
        Client::create($request->only([
            'nombre', 'cif', 'email_contacte', 'telefon', 'direccio'
        ]));

        return redirect()->route('clients.index')
            ->with('success', 'Client creat correctament.');
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

    public function update(Request $request, Client $client)
    {
        $client->update([
            'nombre'         => $request->nombre,
            'cif'            => $request->cif,
            'email_contacte' => $request->email_contacte,
            'telefon'        => $request->telefon,
            'direccio'       => $request->direccio,
            'actiu'          => (bool) $request->input('actiu', $client->actiu),
        ]);

        return redirect()->route('clients.show', $client)
            ->with('success', 'Client actualitzat correctament.');
    }

}
