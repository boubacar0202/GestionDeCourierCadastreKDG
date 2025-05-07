<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvaluationAmenagement extends Model
{
    //
    use HasFactory;
    protected $fillable = [
        'txt_designation_am',
        'nbr_valeur_unitaire_am',
        'nbr_quantile_am',
        'slt_coeficien_am',
        'nbr_valeur_am',
        'nbr_valeur_totale_am',
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
