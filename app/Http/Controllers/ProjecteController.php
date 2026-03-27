<?php

namespace App\Http\Controllers;

use App\Models\Llibres;
use App\Models\Projecte;
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

        return redirect()->route($this->show($projecte->id));
    }

}
