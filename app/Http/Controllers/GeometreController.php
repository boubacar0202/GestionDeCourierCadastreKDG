<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class GeometreController extends Controller
{
    //
    /*
    public function index(){
        return Inertia::render("geometre/index");
    }
    */

    public function create(){
        return Inertia::render("geometre/create");
    }

    public function show()
    {
        $Niveau = 0; // Exemple de récupération d'un nombre depuis la base de données
        return view('geometre/create', compact('Niveau'));
    }

}
