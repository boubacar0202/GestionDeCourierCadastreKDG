<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EvaluationTerrain extends Model
{
    //
    protected $fillable = [
        'txt_date_devaluation',
        'txt_superficie_totale',
        'txt_superficie_bati_sol',
        'slt_secteur',
        'nbr_prix_metre_carré',
        'nbr_valeur_terrain',
    ];

    public function terrain()
    {
        return $this->belongsTo(Terrain::class);
    }
}
