<?php

namespace App\Http\Controllers;

use App\Models\Checkpoint;
use App\Models\Prestecs;
use App\Models\Projecte;
use Illuminate\Http\Request;

class CheckPointController extends Controller
{

    public function retornar(Checkpoint $checkpoint)
    {


        if ($checkpoint-> auth()->id() && !$checkpoint->actiu) {
            $checkpoint->update(['superat' => true]);
        }

        return $checkpoint->fresh();
    }
}
