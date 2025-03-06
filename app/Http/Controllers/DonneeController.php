<?php

namespace App\Http\Controllers;


use App\Models\Terrain;
use Inertia\Inertia;

class DonneeController extends Controller
{
    //
    public function index(){
        return Inertia::render("donnee/create");
    }
    public function create(){


        $terrains = Terrain::with(['region', 'departement','commune', 'arrondissement', 'dossier', 'titulaire', 'references_cadastrales'])->get();

        return Inertia::render("donnee/create", ["terrains"=> $terrains]);
        // 
    }

    public function edit($id)
    {
        $terrain = Terrain::with(['dossier', 'references_cadastrales', 'titulaire'])->findOrFail($id);
    
        return inertia('Secretariat/Create', [
            'terrain' => $terrain
        ]);
    }

}
