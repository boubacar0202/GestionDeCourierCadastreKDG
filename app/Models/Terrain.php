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

    public function region()
    {
        return $this->belongsTo(Region::class);
    }
    public function departement()
    {
        return $this->belongsTo(Departement::class);
    }
    public function arrondissement()
    {
        return $this->belongsTo(Arrondissement::class);
    }
    public function commune()
    {
        return $this->belongsTo(Commune::class);
    }
    public function dossier()
    {
        return $this->belongsTo(Dossier::class);
    }
}
