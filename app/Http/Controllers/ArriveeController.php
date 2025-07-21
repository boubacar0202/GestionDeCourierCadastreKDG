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
 
    public function getNextOrdre()
    {
        $annee = date('Y');
    
        $last = Arrivee::whereYear('created_at', $annee)
            ->orderByRaw('CAST(txt_numdordre AS UNSIGNED) DESC')
            ->first();
    
        $nextOrdre = $last ? ((int) $last->txt_numdordre + 1) : 1;
    
        $numOrdre = sprintf('%05d', $nextOrdre); // ❗️plus de "/année"
    
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
            'dt_datecourier' => 'required|date',
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
        ]);

        // ✅ Récupération du dernier `txt_numdordre` (string formaté)
        // $dernierTxt = DB::table('courierarrivees')
        //     ->selectRaw('MAX(CAST(txt_numdordre AS UNSIGNED)) as max_ordre')
        //     ->value('max_ordre') ?? 0;

        // $nouveau_txt_numdordre = str_pad($dernierTxt + 1, 4, '0', STR_PAD_LEFT);

        // ✅ Récupération du dernier `num_dordre` (integer brut)
        // $dernierNum = DB::table('courierarrivees')->max('num_dordre') ?? 0;
        // $nouveau_num_dordre = $dernierNum + 1;

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
        ]));
        return redirect()->route('instance.create')->with('success', 'Courrier '.$arrivee->txt_numdordre.' modifié avec succès');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
