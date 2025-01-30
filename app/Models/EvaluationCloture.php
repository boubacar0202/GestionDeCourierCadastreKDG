<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EvaluationCloture extends Model
{
    //
    protected $fillable = [
        'chk_perimetre_cloture',
        'nbr_longueur_avant_clo',
        'slt_categorie_clo',
        'nbr_prix_metre_carre_clo',
        'nbr_coefficient_clo',
        'nbr_valeur_clo',
        'nbr_valeur_total_clotur',
    ];

    public function terrain()
    {
        return $this->belongsTo(Terrain::class);
    }
}
