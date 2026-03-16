<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConfiguracioProjecte extends Model
{
    use HasFactory;

    protected $table = 'configuracio_projectes';

    protected $fillable = [
        'projecte_id',
        'plantilla_correus',
        'notificacions_actives',
        'workflow_personalitzat',
        'requereix_aprovacio_client',
    ];

    protected $casts = [
        'notificacions_actives' => 'boolean',
        'workflow_personalitzat' => 'array',
        'requereix_aprovacio_client' => 'boolean',
    ];

    public function projecte(): BelongsTo
    {
        return $this->belongsTo(Projecte::class);
    }
}
