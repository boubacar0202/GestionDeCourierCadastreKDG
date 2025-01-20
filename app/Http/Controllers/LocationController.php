<?php

namespace App\Http\Controllers;

use App\Models\Arrondissement;
use App\Models\Commune;
use App\Models\Departement;
use App\Models\Region;
use Illuminate\Http\Request;
use Inertia\Inertia;

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

    public function getDepartementsByRegion(Request $request)
    {
        $validated = $request->validate([
            'region_id' => 'required|exists:regions,id',
        ]);

        $departements = Departement::where('region_id', $validated['region_id'])->get();

        return Inertia::render('secretariat/create', [
            'departements' => $departements,
        ]);
    }

    public function getArrondissementByDepartement(Request $request)
    {
        $validated = $request->validate([
            'departement_id' => 'required|exists:departements,id',
        ]);

        $arrondissements = Arrondissement::where('departement_id', $validated['departement_id'])->get();

        return Inertia::render('secretariat/create', [
            'arrondissements' => $arrondissements,
        ]);
    }

    public function getCommunesByArrondissement(Request $request)
    {
        $validated = $request->validate([
            'arrondissement_id' => 'required|exists:arrondissements,id',
        ]);

        $communes = Commune::where('arrondissement_id', $validated['arrondissement_id'])->get();

        return Inertia::render('secretariat/create', [
            'communes' => $communes,
        ]);
    }
}
