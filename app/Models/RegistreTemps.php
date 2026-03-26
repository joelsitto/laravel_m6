<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RegistreTemps extends Model
{
    use HasFactory;

    protected $table = 'registre_temps';

    protected $fillable = [
        'ticket_id',
        'user_id',
        'data',
        'hores',
        'descripcio',
    ];

    protected $casts = [
        'data' => 'date',
        'hores' => 'decimal:2',
    ];

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

