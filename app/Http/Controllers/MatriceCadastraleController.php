<?php

namespace App\Http\Controllers;

use App\Models\Terrain;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MatriceCadastraleController extends Controller
{
    //
       /*
    public function index(){
        return Inertia::render("matriceCadastrale/index");
    }
    */

    public function create(){
        $terrains = Terrain::with(['region', 'departement','commune', 'arrondissement', 'dossier', 'titulaire', 'references_cadastrales'])->get();
        
        return Inertia::render("matriceCadastrale/create", ["terrains"=> $terrains]);
    }
}
