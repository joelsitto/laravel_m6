<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;


class Projecte extends Model
{
    protected $fillable = [
        'client_id',
        'gestor_id',
        'nom',
        'descripcio',
        'codi_projecte',
        'estat',
        'data_inici',
        'data_fi_prevista',
        'data_fi_real',
        'pressupost_hores_estimades',
        'pressupost_hores_reals',
    ];

    protected $table = 'projectes';

    protected $casts = [
        'data_inici'        => 'date',
        'data_fi_prevista'  => 'date',
        'data_fi_real'      => 'date',
    ];

    // Un projecte pertany a un client
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    // Un projecte té un gestor (User)
    public function gestor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'gestor_id');
    }

    // Un projecte té molts desenvolupadors (N:N)
    public function desenvolupadors(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'project_user', 'project_id', 'user_id');
    }
}
