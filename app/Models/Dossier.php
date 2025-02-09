<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dossier extends Model
{
    use HasFactory;
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

    public function terrains()
    {
        return $this->hasMany(Terrain::class);
    }

}
