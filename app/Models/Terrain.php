<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Terrain extends Model
{
    //
    use HasFactory;
    protected $fillable = [

        'txt_num_dossier',
        'txt_lotissement',
        'txt_HorsLotissement',
        'txt_num_lotissement',
        'txt_num_section',
        'txt_num_parcelle',
        'txt_num_titre',
        'nbr_surface',
        'txt_appartement',
        'slt_document_admin',
        'txt_num_deliberation',
        'dt_date_deliberation',
        'txt_nicad',
        'slt_region',
        'slt_departement',
        'slt_arrondissement',
        'slt_commune',
        'referencesCadastrale_id',
        'titulaire_id',

    ];


    // Relations
    public function dossier() {
        return $this->belongsTo(Dossier::class, 'txt_num_dossier', 'id');
    }

    public function region()
    {
        return $this->belongsTo(Region::class, 'slt_region', 'id'); // Relation avec la table regions
    }

    public function departement() {
        return $this->belongsTo(Departement::class, 'slt_departement', 'id');
    }

    public function arrondissement() {
        return $this->belongsTo(Arrondissement::class, 'slt_arrondissement', 'id');
    }

    public function commune() {
        return $this->belongsTo(Commune::class, 'slt_commune', 'id');
    }

    
    public function titulaire()
    {
        return $this->belongsTo(Titulaire::class, 'titulaire_id');
    }

    public function references_cadastrales()
    {
        return $this->belongsTo(ReferenceCadastrale::class, 'referencesCadastrale_id');
    }




}
