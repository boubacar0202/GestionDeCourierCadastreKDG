<?php

namespace App\Http\Controllers;

use App\Models\Arrivee;
use App\Models\Depart;
use Illuminate\Http\Request;
use Inertia\Inertia; 
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
class ArriveeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
 
    public function getNextOrdre($annee)
    {
 
        $last = Arrivee::whereYear('dt_datearrivee', $annee) // 🔥 ici on filtre par champ dt_datearrivee, pas created_at !
        ->orderByRaw('CAST(SUBSTRING_INDEX(txt_numdordre, "/", 1) AS UNSIGNED) DESC')
        ->first();
    
        $nextOrdre = $last ? ((int) explode('/', $last->txt_numdordre)[0] + 1) : 1;

        $numOrdre = str_pad($nextOrdre, 5, '0', STR_PAD_LEFT);
    
        return response()->json([
            'num_dordre' => $numOrdre,      // ex: "00001" 
        ]);
    }
     
 
    public function create()
    {
        //
        return Inertia::render("arrivee/create");    
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        // dd($request->all());
        $validateData = $request->validate([
            'txt_numdordre' => 'required|unique:arrivees,txt_numdordre',   
            'txt_caractere' => 'nullable|string|max:255',
            'dt_datearrivee' => 'required|date',
            'txt_numcourier' => 'required|string|max:255',
            'dt_datecourier' => 'required|date|before_or_equal:today',
            'txt_reference' => 'required|string|max:255',
            'txt_categorie' => 'required|string|max:255',
            'txt_designation' => 'required|string|max:255',
            'dt_date' => 'nullable|date',
            'txt_heure' => 'nullable|string|max:255',
            'txt_lieu' => 'nullable|string|max:255',
            'txt_nombrepiece' => 'required|integer',
            'txt_objet' => 'required|string|max:255',
            'txt_expediteur' => 'required|string|max:255',
            'txt_agenttraiteur' => 'required|string|max:255',
            'txt_observation' => 'nullable|string|max:255',
            'fichierPDF' => 'nullable|file|mimes:pdf|max:120400',

        ],[
            'txt_numdordre.unique' => 'Le numéro d\'ordre existe déjà.',
            'txt_numdordre.required' => 'Le numéro d\'ordre est requis.',
            'txt_numcourier.required' => 'Le numéro du courrier est requis.',
            'dt_datearrivee.required' => 'La date d\'arrivée est requise.',
            'dt_datearrivee.date' => 'Le format de la date d\'arrivée est invalide.',
            'dt_datecourier.required' => 'La date du courrier est requise.',
            'dt_datecourier.before_or_equal' => 'La date du courrier ne peut pas dépasser aujourd\'hui.',
            'txt_reference.required' => 'La référence est requise.',
            'txt_categorie.required' => 'La catégorie est requise.',
            'txt_designation.required' => 'La désignation est requise.',
            'txt_nombrepiece.required' => 'Le nombre de pièces est requis.',
            'txt_nombrepiece.integer' => 'Le nombre de pièces doit être un entier.',
            'txt_objet.required' => 'L\'objet est requis.',
            'txt_expediteur.required' => 'L\'expéditeur est requis.',
            'txt_agenttraiteur.required' => 'L\'agent traitant est requis.',
            'fichierPDF.mimes' => 'Le fichier doit être au format PDF.',
            'fichierPDF.max' => 'La taille du fichier ne doit pas dépasser 120 Mo.',
        ]);
        // Gestion du fichier PDF

        if ($request->hasFile('fichierPDF')) {
            $validateData['fichierPDF'] = $request->file('fichierPDF')->store('courrierarrivee', 'public');
        }

        Arrivee::create([
            'txt_numdordre' => $validateData['txt_numdordre'],  
            'txt_caractere' => $validateData['txt_caractere'] ?? null,
            'dt_datearrivee' => $validateData['dt_datearrivee'],
            'txt_numcourier' => $validateData['txt_numcourier'],
            'dt_datecourier' => $validateData['dt_datecourier'],
            'txt_reference' => $validateData['txt_reference'],
            'txt_categorie' => $validateData['txt_categorie'],
            'txt_designation' => $validateData['txt_designation'],
            'dt_date' => $validateData['dt_date'],  
            'txt_heure' => $validateData['txt_heure'],
            'txt_lieu' => $validateData['txt_lieu'],
            'txt_nombrepiece' => $validateData['txt_nombrepiece'],
            'txt_objet' => $validateData['txt_objet'],
            'txt_expediteur' => $validateData['txt_expediteur'],
            'txt_agenttraiteur' => $validateData['txt_agenttraiteur'],
            'txt_observation' => $validateData['txt_observation'] ?? null, 
            'fichierPDF'=> $validateData['fichierPDF'] ?? null,
        ]);

        return redirect()->route('arrivee.create')->with('success', 'Courrier arrivée créé avec succès');
  
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function editarrivee($id)
    {
 
      
        $arrivees = Arrivee::findOrFail($id); 

        return Inertia::render('arrivee/editarrivee', [
            'arrivees' => $arrivees, 
        ]);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $arrivee = Arrivee::findOrFail($id); 
        // ✅ Mise à jour du arrivee (champs directs)
        $arrivee->update($request->only([
            'txt_numdordre',
            'txt_caractere',
            'dt_datearrivee',
            'txt_numcourier',
            'dt_datecourier',
            'txt_reference',
            'txt_categorie',
            'txt_designation',
            'dt_date',
            'txt_heure',
            'txt_lieu',
            'txt_nombrepiece',
            'txt_objet',
            'txt_expediteur',
            'txt_agenttraiteur',
            'txt_observation',
            'fichierPDFcd',
        ]));
        return redirect()->route('instancearrivee.create')->with('success', 'Courrier '.$arrivee->txt_numdordre.' modifié avec succès');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
