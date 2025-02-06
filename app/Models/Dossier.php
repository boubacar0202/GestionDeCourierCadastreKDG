<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dossier extends Model
{
   
    protected $fillable = [
        "numDossier",
        "numDordre",
        "serviceRendu",
        "etatCession",
        "cessionDefinitive",
        "dateCreation",
    ];
    protected $casts = [
        'dateCreation' => 'date',
    ];

}
