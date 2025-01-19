<?php

namespace App\Http\Controllers;

use App\Models\Arrondissement;
use App\Models\Commune;
use App\Models\Departement;
use App\Models\Donnee;
use App\Models\Region;
use App\Models\Dossier;
use Illuminate\Foundation\Testing\Concerns\InteractsWithAuthentication;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SecretariatController extends Controller
{
    //
    public function index()
    {
        $donnee = Donnee::all();

        return Inertia::render("secretariat/index",  ["donnees" => $donnee]);
    }

    public function create()
    {
        $regions = Region::all();
        // $departements = Departement::all();
        // $arrondissements = Arrondissement::all();
        // $communes = Commune::all();

        return Inertia::render("secretariat/create",  [
            "regions" => $regions,
            "departements" => [],
            // "arrondissements" => $arrondissements,
            // "communes" => $communes,
        ]);
    }

    public function store(Request $request){
        $dossier = new Dossier;
        $dossier->txt_num_dossier = $request->txt_num_dossier;
        $dossier->txt_num_dordre = $request->txt_num_dordre;
        $dossier->slt_service_dendu = $request->slt_service_dendu;
        $dossier->txt_etat_cession = $request->txt_etat_cession;
        $dossier->txt_cession_definitive = $request->txt_cession_definitive;
        $dossier->dt_date_creation = $request->dt_date_creation;
        /*
        "txt_num_dossier",
        "txt_num_dordre",
        "slt_service_dendu",
        "txt_etat_cession",
        "txt_cession_definitive",
        "dt_date_creation",
        */
        $dossier->save();
        return redirect()->route('secretariat.create')->with('success ', 'Enregistrement réussit');
    }
}
