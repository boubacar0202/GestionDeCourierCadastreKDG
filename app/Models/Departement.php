<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Departement extends Model
{
    //
    protected $fillable = [
        'slt_departement',
    ];

    public function region()
    {
        return $this->belongsTo(Region::class);
    }

    public function arrondissement()
    {
        return $this->hasMany(Arrondissement::class);
    }
}
