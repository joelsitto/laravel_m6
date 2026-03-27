<?php

namespace App\Http\Controllers;

use App\Models\Bibliotecaris;
use App\Models\Llibres;
use App\Models\Projecte;
use App\Models\Supervisor;
use Illuminate\Http\Request;

class ProjecteController extends Controller
{
    public function create()
    {
        return view('projecte.create');
    }


    public function show(Projecte $projecte)
    {
        $projecte->load(['checkpoints']);

        return $projecte;
    }

    public function store(Request $request)
    {
        $projecte = Projecte::create($request->only([
            'nom', 'estat'
        ]));

        return $projecte;
    }

    public function delete(Projecte $projecte)
    {
        $projecte->delete();

    }

    public function nochk()
    {
        return Projecte::whereDoesntHave('checkpoints')->get();
    }

    public function assigna(Projecte $projecte, Supervisor $supervisor)
    {

        $projecte->supervisors()->attach($supervisor->id);
        $projecte->load(['checkpoints','supervisors']);

        return $projecte;
    }

    public function edit(Projecte $projecte)
    {
        return view('projecte.edit', compact('projecte'));
    }

    public function update(Request $request, Projecte $projecte)
    {
        $projecte->update($request->only(['nom', 'estat']));

        $projecte->load(['checkpoints']);

        return $projecte;
    }
}
