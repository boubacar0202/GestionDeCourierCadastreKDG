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
    public function create()
    {
        $terrains = Terrain::with([
            'region',
            'departement',
            'commune',
            'arrondissement',
            'dossier',
            'titulaire',
            'references_cadastrales',
            'references_usages',
        ])->get();
    
        $terrain = Terrain::with('references_usages')
            ->where('txt_nicad', session('txt_nicad'))
            ->first();
    
        return Inertia::render('donnee/create', [
            'terrain' => $terrain,
            'terrains' => $terrains,
        ]);
    }
}
