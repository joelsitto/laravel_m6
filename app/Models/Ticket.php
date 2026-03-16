<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Database\Factories\TicketFactory;

class Ticket extends Model
{
    /** @use HasFactory<TicketFactory> */
    use HasFactory;

    protected $fillable = [
        'projecte_id',
        'creador_id',
        'codi_ticket',
        'titol',
        'descripcio',
        'estat',
    ];

    public function projecte(): BelongsTo
    {
        return $this->belongsTo(Projecte::class);
    }

    public function creador(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creador_id');
    }

    public function comentaris(): HasMany
    {
        return $this->hasMany(Comentari::class);
    }
}


