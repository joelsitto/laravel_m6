<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supervisor extends Model
{
    protected $table = 'supervisors';
    protected $fillable = ['nom', 'projecte_id'];


    public function projectes()
    {
        return $this->belongsToMany(Projecte::class);
    }
}
