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
        'txt_num_parcelle',
        'txt_num_dossier',
    ];

    // public function terrain()
    // {
    //     return $this->belongsTo(Terrain::class);
    // }

    
    public function terrain() {
        return $this->belongsTo(Terrain::class, 'txt_num_parcelle', 'id');
    }

    public function dossier() {
        return $this->belongsTo(Dossier::class, 'txt_num_dossier', 'id');
    }
}
