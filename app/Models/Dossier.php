<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dossier extends Model
{
    protected $table = 'dossiers';
    protected $fillable = [
        "numDossier",
        "numDordre",
        "servicRendu",
        "etatCession",
        "cessionDefinitive",
        "dateCreation",

    ];
    protected $casts = [
        'dateCreation' => 'date',
    ];

    public function region()
    {
        return $this->hasOne(Region::class);
    }
    
    public function terrains()
    {
        return $this->hasMany(Terrain::class);
    }

}
