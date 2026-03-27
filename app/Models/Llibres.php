<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Llibres extends Model
{
    protected $table = 'llibres';
    protected $fillable = ['titol', 'categoria'];

    public function prestecs()
    {
        return $this->hasMany(Prestecs::class, 'llibre_id');
    }


    public function bibliotecaris(): BelongsToMany
    {
        return $this->belongsToMany(Bibliotecaris::class, 'bibliotecari_llibre', 'llibre_id', 'bibliotecari_id');
    }

}
