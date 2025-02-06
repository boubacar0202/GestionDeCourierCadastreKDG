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
        'txt_num_titre',
        'nbr_surface',
        'slt_document_admin',
        'txt_num_deliberation',
        'dt_date_deliberation',
        'txt_nicad',
        'region_id',
        'departement_id',
        'arrondissement_id',
        'commune_id',
        'dossier_id',
    ];

    public function region()
    {
        return $this->belongsTo(Region::class,  'region_id', 'id');
    }

    public function departement()
    {
        return $this->belongsTo(Departement::class, 'departement_id', 'id');
    }

    public function arrondissement()
    {
        return $this->belongsTo(Arrondissement::class, 'arrondissement_id', 'id');
    }

    public function commune()
    {
        return $this->belongsTo(Commune::class, 'commune_id', 'id');
    }

    // public function dossier()
    // {
    //     return $this->belongsTo(Dossier::class, 'dossier_id', 'id');
    // }
    public function dossier()
    {
        return $this->belongsTo(Dossier::class, 'dossier_id', 'id');
    }

}
