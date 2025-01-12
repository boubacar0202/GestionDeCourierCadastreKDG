<?php

namespace App\Http\Controllers;

use App\Models\Arrondissement;
use App\Models\Commune;
use App\Models\Departement;
use App\Models\Donnee;
use App\Models\Region;
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
            // "departements" => $departements,
            // "arrondissements" => $arrondissements,
            // "communes" => $communes,
        ]);
    }
}
