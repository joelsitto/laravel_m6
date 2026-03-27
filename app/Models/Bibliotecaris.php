<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Bibliotecaris extends Model
{
    protected $table = 'bibliotecaris';
    protected $fillable = ['nom'];


    public function llibres(): BelongsToMany
    {
        return $this->belongsToMany(Llibres::class, 'bibliotecari_llibre', 'bibliotecari_id', 'llibre_id');
    }
}
