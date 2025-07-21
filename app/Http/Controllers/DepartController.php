<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB; 
use App\Models\Depart;
use App\Models\Arrivee;
use Illuminate\Http\Request;
use Inertia\Inertia;
class DepartController extends Controller
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
    public function create()
    {
        //
        return Inertia::render("depart/create");
    }

    /**
     * Store a newly created resource in storage.
    */

    // retrouver le dernier numéro dordre 
    public function getNextOrdre()
    {
        $annee = date('Y');
    
        $last = Depart::whereYear('created_at', $annee)
            ->orderByRaw('CAST(txt_numdordrecd AS UNSIGNED) DESC')
            ->first();
    
        $nextOrdre = $last ? ((int) $last->txt_numdordrecd + 1) : 1;
    
        $numOrdre = sprintf('%04d', $nextOrdre); // ❗️plus de "/année"
    
        return response()->json([
            'num_dordre' => $numOrdre,      // ex: "00001" 
        ]);
    }
 
    public function fetchReferencesArrivee(Request $request): JsonResponse
    {
        $references = Arrivee::where('txt_categorie', 'Demande SERVICES')
        ->pluck('txt_reference')->toArray();

        // Association txt_reference => txt_expediteur
        $map_ref_to_expediteur = Arrivee::pluck('txt_expediteur', 'txt_reference')->toArray();
        $map_ref_to_objet = Arrivee::pluck('txt_objet', 'txt_reference')->toArray();

        return response()->json([
            'references' => $references,
            'map_ref_to_expediteur' => $map_ref_to_expediteur,
            'map_ref_to_objet' => $map_ref_to_objet,
        ]);
    }
 

    public function store(Request $request)
    {
        //
        $request->validate([  
            'txt_numdordrecd' => 'required|unique:departs,txt_numdordrecd',  
            'txt_caracterecd' => 'nullable|string|max:255',
            'dt_datecouriercd' => 'required|date',
            'txt_categoriecd' => 'required|string|max:255',
            'txt_referencecourierarriveecd' => 'nullable|string|max:255',
            'txt_referencecourierdepartcd'=> 'nullable|string|max:255',
            'txt_nombrepiececd' => 'required|string|max:255',
            'txt_referencecd' => 'nullable|string|max:255',
            'txt_objetcd' => 'required|string|max:255',
            'txt_destinatairecd' => 'required|string|max:255',
            'dt_dateenvoicd' => 'required|date|before_or_equal:today',
            'txt_referencereceptioncd' => 'nullable|string|max:255',
            'txt_observationcd' => 'nullable|string|max:255',
            'txt_dureetraitementcd' => 'required|string|max:255', 
        ]);

        // Validation conditionnelle
 

        Depart::create([ 
            'txt_numdordrecd' => $request->txt_numdordrecd,
            'txt_caracterecd' => $request->txt_caracterecd ?? null,
            'dt_datecouriercd' => $request->dt_datecouriercd,
            'txt_categoriecd' => $request->txt_categoriecd,
            'txt_referencecourierarriveecd' => $request->txt_referencecourierarriveecd ?? null,
            'txt_referencecourierdepartcd' => $request->txt_referencecourierdepartcd ?? null,
            'txt_nombrepiececd' => $request->txt_nombrepiececd,
            'txt_referencecd' => $request->txt_referencecd ?? null,
            'txt_objetcd' => $request->txt_objetcd,
            'txt_destinatairecd' => $request->txt_destinatairecd,
            'dt_dateenvoicd' => $request->dt_dateenvoicd,
            'txt_referencereceptioncd' => $request->txt_referencereceptioncd ?? null,
            'txt_observationcd' => $request->txt_observationcd ?? null,
            'txt_dureetraitementcd' => $request->txt_dureetraitementcd,
        ]);
        return redirect()->route('depart.create')->with('success', 'Dossier enregistré avec succès');

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
    public function editdepart($id)
    {
        //
        $departs = Depart::findOrFail($id); 
 
        return Inertia::render('depart/editdepart', [
            'departs' => $departs,   
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update (Request $request, string $id)
    {
        //
        $depart = Depart::findOrFail($id); 
        // ✅ Mise à jour du arrivee (champs directs)
        $depart->update($request->only([
            'txt_numdordrecd',
            'txt_caracterecd',
            'dt_datecouriercd',
            'txt_numcouriercd',
            'txt_referencecd',
            'txt_categoriecd',
            'txt_designationcd',
            'dt_datecd',
            'txt_heurecd',
            'txt_lieucd',
            'txt_nombrepiececd',
            'txt_objetcd',
            'txt_destinatairecd',
            'dt_dateenvoicd',
            'txt_referencereceptioncd',
            'txt_referencecourierdepartcd',
            'txt_observationcd',
            'txt_dureetraitementcd',
        ]));

        return redirect()->route('instance.create')->with('success', 'Courrier '.$depart->txt_numdordrecd.' modifié avec succès');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
