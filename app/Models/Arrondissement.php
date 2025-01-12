<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Arrondissement extends Model
{
    //
    protected $fillable = [
        'slt_arrondissement',
    ];

    protected $with = ['communes'];

    public function departement()
    {
        return $this->belongsTo(Departement::class);
    }

    public function communes()
    {
        return $this->hasMany(Commune::class);
    }
}
