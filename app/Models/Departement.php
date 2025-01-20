<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Departement extends Model
{
    //
    protected $fillable = [
        'slt_departement',
    ];

    protected $with = ['arrondissements'];

    public function regions()
    {
        return $this->belongsTo(Region::class);
    }

    public function arrondissements()
    {
        return $this->hasMany(Arrondissement::class);
    }
}
