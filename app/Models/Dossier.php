<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dossier extends Model
{

    use HasFactory;



    protected $fillable = [
        // 'nextslug',
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


    public function terrains()
    {
        return $this->hasMany(Terrain::class);
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($dossier) {
            // Récupérer le dernier dossier créé cette année
            $lastDossier = self::whereYear('created_at', date('Y'))
                ->orderBy('id', 'desc')
                ->first();
                
            // Générer le numéro séquentiel
            $number = $lastDossier ? (int)substr($lastDossier->txt_num_dossier, 0, 6) + 1 : 1;
            
            // Formatage avec 6 chiffres + année courante
            $dossier->txt_num_dossier = sprintf('%06d/%s', $number, date('Y'));
        });
    }


}
