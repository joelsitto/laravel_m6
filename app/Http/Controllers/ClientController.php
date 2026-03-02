<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index()
    {
        $clients = Client::withCount('projectes')->where('actiu', true)->paginate(15);

        return view('clients.index', compact('clients'));
    }

    public function create()
    {
        return view('clients.Form');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre'         => ['required', 'string', 'max:255'],
            'cif'            => ['required', 'string', 'unique:clients,cif'],
            'email_contacte' => ['required', 'email'],
            'telefon'        => ['nullable', 'string', 'max:20'],
            'direccio'       => ['nullable', 'string'],
        ]);

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
        $request->validate([
            'nombre'         => ['required', 'string', 'max:255'],
            'cif'            => ['required', 'string', 'unique:clients,cif,' . $client->id],
            'email_contacte' => ['required', 'email'],
            'telefon'        => ['nullable', 'string', 'max:20'],
            'direccio'       => ['nullable', 'string'],
            'actiu'          => ['boolean'],
        ]);

        $client->update($request->only([
            'nombre', 'cif', 'email_contacte', 'telefon', 'direccio', 'actiu'
        ]));

        return redirect()->route('clients.show', $client)
            ->with('success', 'Client actualitzat correctament.');
    }

    public function projectes(Client $client)
    {
        $client->load('projectes.gestor');

        return view('clients.projectes', compact('client'));
    }
}
