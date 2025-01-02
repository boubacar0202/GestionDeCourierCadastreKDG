<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReferenceUsage extends Model
{
    //
    protected $fillable = [
        'slt_reference_usage',
        'txt_occupan_habitaion_1',
        'txt_activite_principal_hbt_1',
        'txt_ninea_occupan_hbt_1',
        'tel_tel_occupant_hbt_1',
        'nbr_montant_loyer_hbt_1',
        'txt_activite_commercial',
        'txt_occopan_commercial',
        'txt_activite_industriel',
        'txt_occopan_industriel',
        'txt_activite_agricole',
        'txt_occopan_agricole',
        'txt_activite_professionnelle',
        'txt_occopan_professionnelle',
        'txt_activite_culte',
        'txt_occopan_culte',
        'txt_Activite_administratif',
        'txt_occupan_administratif',
    ];
}
