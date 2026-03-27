<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Checkpoint extends Model
{
    protected $table = 'checkpoints';
    protected $fillable = ['projecte_id', 'superat', 'observacions'];

    protected $casts = [
        'superat' => 'boolean',
    ];


    public function projecte(): BelongsTo
    {
        return $this->belongsTo(Projecte::class);
    }
}
