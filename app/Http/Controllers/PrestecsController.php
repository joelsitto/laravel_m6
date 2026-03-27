<?php

namespace App\Http\Controllers;

use App\Models\Llibres;
use App\Models\Prestecs;
use App\Models\User;

class PrestecsController extends Controller
{
    public function create(Llibres $llibre, User $user)
    {
        return $prestec = Prestecs::create([
            'usuari_id' => $user->id,
            'llibre_id' => $llibre->id,
            'actiu' => true,
            'data' => now(),
        ]);
    }

    public function retornar(int $idPrestec)
    {
        $prestec = Prestecs::findOrFail($idPrestec);

        if ($prestec->usuari_id === auth()->id() && $prestec->actiu) {
            $prestec->update(['actiu' => false]);
        }

        return $prestec->fresh();
    }
}
