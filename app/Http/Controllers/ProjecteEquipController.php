<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProjecteEquipRequest;
use App\Models\Projecte;
use App\Models\User;

class ProjecteEquipController extends Controller
{
    public function index(Projecte $projecte)
    {
        $this->authorize('update', $projecte);

        $projecte->load('desenvolupadors');

        $devsDisponibles = User::query()
            ->where('rol', 'DESENVOLUPADOR')
            ->whereDoesntHave('projectes', function ($query) use ($projecte) {
                $query->where('projectes.id', $projecte->id);
            })
            ->orderBy('name')
            ->get();

        return view('projectes.equip.index', compact('projecte', 'devsDisponibles'));
    }

    public function store(StoreProjecteEquipRequest $request, Projecte $projecte)
    {
        $this->authorize('update', $projecte);

        $userId = (int) $request->validated('user_id');
        $projecte->desenvolupadors()->syncWithoutDetaching([$userId]);

        return redirect()->route('projectes.equip.index', $projecte)
            ->with('success', 'Membre afegit correctament.');
    }

    public function destroy(Projecte $projecte, User $user)
    {
        $this->authorize('update', $projecte);

        $projecte->desenvolupadors()->detach($user->id);

        return redirect()->route('projectes.equip.index', $projecte)
            ->with('success', 'Membre eliminat correctament.');
    }
}

