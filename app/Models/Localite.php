<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Localite extends Model
{
    //
    protected $fillable = [
        'region',
        'departement',
        'arrondissement',
        'commune',
    ];
}
