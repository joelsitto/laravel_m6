<?php

namespace App\Models;

use Database\Factories\TicketFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ticket extends Model
{
    /** @use HasFactory<TicketFactory> */
    use HasFactory;

    protected $fillable = [
        'projecte_id',
        'creador_id',
        'assignat_a',
        'ticket_pare_id',
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

    public function assignat(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assignat_a');
    }

    public function pare(): BelongsTo
    {
        return $this->belongsTo(Ticket::class, 'ticket_pare_id');
    }

    public function fills(): HasMany
    {
        return $this->hasMany(Ticket::class, 'ticket_pare_id');
    }

    public function comentaris(): HasMany
    {
        return $this->hasMany(Comentari::class);
    }

    public function registresTemps(): HasMany
    {
        return $this->hasMany(RegistreTemps::class);
    }
}
