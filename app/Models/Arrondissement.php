<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Arrondissement extends Model
{
    //
    protected $fillable =[
  
        'slt_arrondissement',
    ];

    public function departement()
    {
        return $this->belongsTo(Departement::class);
    }

    public function commune()
    {
        return $this->hasMany(Commune::class);
    }
}


