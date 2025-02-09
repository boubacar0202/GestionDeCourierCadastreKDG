<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dossier extends Model
{
<<<<<<< HEAD
    use HasFactory;
=======

>>>>>>> f532143f9baf5b101836f86b9efee2216f2d363d
    protected $fillable = [
        "txt_num_dossier",
        "txt_num_dordre",
        "slt_service_rendu",
        "txt_etat_cession",
        "txt_cession_definitive",
        "dt_date_creation",
    ];
    protected $casts = [
        'dt_date_creation' => 'date',
    ];
<<<<<<< HEAD

    public function terrains()
    {
        return $this->hasMany(Terrain::class);
    }

=======
>>>>>>> f532143f9baf5b101836f86b9efee2216f2d363d
}
