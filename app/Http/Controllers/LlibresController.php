<?php

namespace App\Http\Controllers;

use App\Models\Bibliotecaris;
use App\Models\Llibres;
use Illuminate\Http\Request;

class LlibresController extends Controller
{
    public function index()
    {
        return Llibres::all();
    }

    public function create()
    {
        return view('llibres.create');
    }

    public function store(Request $request)
    {
        Llibres::create($request->only([
            'titol', 'categoria'
        ]));

        return redirect()->route('llibres.index');
    }

    public function delete(Llibres $llibres)
    {
        $llibres->delete();

        return redirect()->route('llibres.index');
    }

    public function edit(Llibres $llibres)
    {
        return view('llibres.modificar', compact('llibres'));
    }

    public function update(Request $request, Llibres $llibres)
    {
        $llibres->update($request->only(['titol', 'categoria']));
        return redirect()->route('llibres.index');
    }

    public function assignarBibliotecari(int $idLlibre, int $idBibliotecari)
    {
        // 1) Buscar el libro y el bibliotecario por id
        $llibre = Llibres::findOrFail($idLlibre);
        $bibliotecari = Bibliotecaris::findOrFail($idBibliotecari);

        // 2) Si no está asignado, lo asignamos
        if (!$llibre->bibliotecaris->contains($bibliotecari->id)) {
            $llibre->bibliotecaris()->attach($bibliotecari->id);
        }

        // 3) Devolver el libro con sus bibliotecarios
        return $llibre->load('bibliotecaris');
    }

    public function sensePrestecs()
    {
        // Libros que no tienen ningún préstamo
        return Llibres::whereDoesntHave('prestecs')->get();
    }

}
