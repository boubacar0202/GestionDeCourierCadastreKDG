<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReferenceCadastrale extends Model
{
    protected $fillable = [
        'rd_immatriculation_terrain',
        'slt_dependant_domaine',
        'slt_bornage',
        'ussu_bornage',
        'txt_titre_mere',
        'slt_lf',
        'txt_num_requisition',
        'txt_surface_bornage',
        'dt_date_bornage',
        'txt_nom_geometre',
    ];
}
