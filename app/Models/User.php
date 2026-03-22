<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'rol',
        'tarifa_hora',
        'client_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'tarifa_hora' => 'decimal:2',
        ];
    }


    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function projectesGestionats(): HasMany
    {
        return $this->hasMany(Projecte::class, 'gestor_id');
    }

    public function projectes(): BelongsToMany
    {
        return $this->belongsToMany(Projecte::class, 'project_user');
    }

    public function ticketsCreats(): HasMany
    {
        return $this->hasMany(Ticket::class, 'creador_id');
    }

    public function comentaris(): HasMany
    {
        return $this->hasMany(Comentari::class, 'autor_id');
    }

    public function hasRole(string ...$roles): bool
    {
        $currentRole = strtoupper(trim((string) $this->rol));

        foreach ($roles as $role) {
            $role = strtoupper(trim($role));
            if ($role === 'DEV') {
                $role = 'DESENVOLUPADOR';
            }

            if ($currentRole === $role) {
                return true;
            }
        }

        return false;
    }
}
