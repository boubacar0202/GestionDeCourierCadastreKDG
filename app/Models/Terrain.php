<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Terrain extends Model
{
    //
    protected $fillable = [
        'txt_lotissement',
        'txt_num_lotissement',
        'txt_num_section',
        'txt_num_parcelle',
        'nbr_surface',
        'slt_document_admin',
        'txt_num_deliberation',
        'dt_date_deliberation',
        'txt_nicad',
    ];
}
