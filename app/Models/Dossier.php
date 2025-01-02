<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dossier extends Model
{
    protected $fillable = [
        "txt_num_dossier",
        "txt_num_dordre",
        "slt_service_dendu",
        "txt_etat_cession",
        "txt_cession_definitive",
        "dt_date_creation",

    ];

   
}
