<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;


class Projecte extends Model
{
    use HasFactory;
    protected $fillable = [
        'client_id',
        'gestor_id',
        'nom',
        'descripcio',
        'codi_projecte',
        'estat',
        'data_inici',
        'data_fi_prevista',
        'pressupost_hores_previstes',
    ];

    protected $table = 'projectes';

    protected $casts = [
        'data_inici'        => 'date',
        'data_fi_prevista'  => 'date',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function gestor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'gestor_id');
    }

    public function desenvolupadors(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'project_user', 'project_id', 'user_id');
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }

    public function configuracio(): HasOne
    {
        return $this->hasOne(ConfiguracioProjecte::class);
    }
}
