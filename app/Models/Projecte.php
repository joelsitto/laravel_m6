<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Projecte extends Model
{
    protected $table = 'projectes';
    protected $fillable = ['nom', 'estat'];

    public function checkpoints()
    {
        return $this->hasMany(Checkpoint::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function supervisors()
    {
        return $this->belongsToMany(Supervisor::class);
    }

}
