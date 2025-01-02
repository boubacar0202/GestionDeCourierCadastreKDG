<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EvaluationBati extends Model
{
    //
    protected $fillable = [
        'slt_type_residence',
        'rd_type_maissons',
        'chk_bati_principal',
        'slt_cat',
        'nbr_prix_metre_carre',
        'nbr_surface_bati_sol',
        'nbr_niveau',
        'nbr_surface_brute',
        'nbr_surface_utile',
        'slt_coeff',
        'nbr_surface_corriger',
        'nbr_valeur',
        'txt_valeur_terrain_bati',
    ];
}
