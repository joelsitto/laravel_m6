<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Prestecs extends Model
{
    protected $table = 'prestecs';
    protected $fillable = ['usuari_id', 'llibre_id', 'actiu', 'data'];
    protected $casts = [
        'actiu' => 'boolean',
    ];


    public function llibre(): BelongsTo
    {
        return $this->belongsTo(Llibres::class);
    }

    public function usuari(): BelongsTo
    {
        return $this->belongsTo(User::class, 'usuari_id');
    }
}
