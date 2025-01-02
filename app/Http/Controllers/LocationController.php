<?php

namespace App\Http\Controllers;

use App\Models\Arrondissement;
use App\Models\Commune;
use App\Models\Departement;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    //
    public function getDepartements()
    {
        $departements = Departement::all();
        return response()->json($departements);
    }

    public function getArrondissements($departementId)
    {
        return Arrondissement::where('departement_id', $departementId)->get();
    }

    public function getCommunes($arrondissementId)
    {
        return Commune::where('arrondissement_id', $arrondissementId)->get();
    }
}
