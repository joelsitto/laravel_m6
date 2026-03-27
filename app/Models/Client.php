<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Client extends Model
{
    protected $table = 'clients';
    protected $fillable = ['nombre', 'cif', 'email_contacte', 'telefon', 'direccio', 'actiu'];
    protected $casts = [
        'actiu' => 'boolean',
    ];

    public function projectes(): HasMany
    {
        return $this->hasMany(ProjecteAntic::class);
    }
}
