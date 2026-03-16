<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Database\Factories\ComentariFactory;

class Comentari extends Model
{
    /** @use HasFactory<ComentariFactory> */
    use HasFactory;

    protected $fillable = [
        'ticket_id',
        'autor_id',
        'text',
    ];

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }

    public function autor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'autor_id');
    }
}


