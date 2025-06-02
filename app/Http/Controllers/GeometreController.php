<?php

namespace App\Http\Controllers;

use App\Models\Dossier;
use App\Models\EvaluationAmenagement;
use App\Models\EvaluationBati;
use App\Models\EvaluationCloture;
use App\Models\EvaluationCoursAmenagee;
use App\Models\EvaluationTerrain;
use App\Models\ReferenceUsage;
use App\Models\Region;
use App\Models\Terrain; 
use Illuminate\Http\Request;
use Inertia\Inertia;

class GeometreController extends Controller
{
    //
   
    public function index(){
        return Inertia::render("geometre/index");
    }
 
    public function create(){

        // Récupérer le dossier par son numéro
        $dossier = Dossier::where('txt_num_dossier', session('txt_num_dossier'))->first();

        // Récupérer le dossier par son numéro
        $terrain = Terrain::where('nbr_surface', session('nbr_surface'))->first();

        // Récupérer le terrain associé via l'ID du dossier
        $terrain = $dossier ? Terrain::where('txt_num_dossier', $dossier->id)->first() : null;
        
        $terrain = Terrain::where('txt_nicad', session('txt_nicad'))->first();

        return Inertia::render('geometre/create', [
            'terrain' => $terrain,
            'txt_nicad' => $terrain?->txt_nicad,
            'nbr_surface' => $terrain?->nbr_surface, 
        ]);
    }


    public function show()
    {
        $Niveau = 0; // Exemple de récupération d'un nombre depuis la base de données
        return view('geometre/create', compact('Niveau'));
    }
    
    public function verify(Request $request)
    {
        $request->validate([
            'txt_num_dossier' => 'required|string',
        ]);
    
        $dossier = Dossier::where('txt_num_dossier', $request->txt_num_dossier)->first();
 
        if ($dossier) {
            // Pas de redirection — juste retour d'un flag JSON
            return response()->json([
                'success' => 'Dossier trouvé !',
                'exists' => true,
            ], 200);
        } else {
            return response()->json([
                'errors' => [
                    'txt_num_dossier' => 'Numéro introuvable.'
                ]
            ], 422);
        }
    }


     // PAGES GEOMETRE
    public function store(Request $request)
    {
        // dd($request->all());
        $request->merge([
            'slt_categoriePR' => (string) $request->slt_categoriePR,
        ]);

        $validatedData = $request->validate([
        //table Reference_usage
        'txt_num_dossier' => 'required|string|exists:dossiers,txt_num_dossier',
        'txt_nicad' => 'required|exists:terrains,txt_nicad',
 
        // usage
        'slt_residence' => 'nullable|string',
        'slt_usage' => 'nullable|string',
        'occupants' => 'required|array',
        'occupants.*.txt_nomOccupantTG' => 'nullable|string',
        'occupants.*.txt_numAppartementTG' => 'nullable|string',
        'occupants.*.txt_activiteTG' => 'nullable|string',
        'occupants.*.txt_nineaTG' => 'nullable|string',
        'occupants.*.tel_telephoneTG' => 'nullable|string',
        'occupants.*.nbr_montantLoyerTG' => 'nullable|numeric',
        'occupants.*.txt_dateLieuNaissanceTG' => 'nullable|string',
        'occupants.*.txt_cniPasseportTG' => 'nullable|string',
        'occupants.*.dt_dateDelivranceTG' => 'nullable|date',

        // Bti 
        'txt_dependant_domainePR'   =>  'nullable|string',
        'slt_categoriePR'   =>  'nullable|string', 
        'nbr_prix_metre_carrePR'    =>  'nullable|numeric',
        'nbr_surface_bati_solPR'    =>  'nullable|numeric',
        'nbr_niveauPR'  =>  'nullable|numeric',
        'nbr_surface_utilePR'   =>  'nullable|numeric',
        'slt_coeffPR'   =>  'nullable|numeric',
        'nbr_surface_corrigerPR'    =>  'nullable|numeric',
        'nbr_valeurPR'  =>  'nullable|numeric',

        'txt_valeur_terrain_bati' =>'nullable|numeric',
        'currentCat' => 'required|string', 
        'occupantsBP' => 'required|array',
        'occupantsBP.*.slt_dependant_domaineTG' => 'nullable|string',
        'occupantsBP.*.slt_categorieTG' =>  'nullable|numeric',
        'occupantsBP.*.nbr_prix_metre_carreTG' => 'nullable|numeric',
        'occupantsBP.*.nbr_surface_bati_solTG' => 'nullable|numeric',
        'occupantsBP.*.nbr_niveauTG' => 'nullable|numeric',
        'occupantsBP.*.nbr_surface_utileTG' => 'nullable|numeric',
        'occupantsBP.*.slt_coeffTG' => 'nullable|numeric',
        'occupantsBP.*.nbr_surface_corrigerTG' => 'nullable|numeric',
        'occupantsBP.*.nbr_valeurTG' => 'nullable|numeric',

        // Cours Amenager
        'nbr_valeur_total_ca' => 'nullable|numeric',
        'occupantsCA' => 'required|array', 
        'occupantsCA.*.slt_cours_amenager_totale' => 'nullable|string',
        'occupantsCA.*.nbr_surface_ca_total'      => 'nullable|numeric',
        'occupantsCA.*.slt_categorie_ca_total'    => 'nullable|string',
        'occupantsCA.*.nbr_prix_metre_carre_ca_total' => 'nullable|numeric',
        'occupantsCA.*.nbr_coefficient_ca_total'  => 'nullable|numeric',
        'occupantsCA.*.nbr_valeur_ca_total'       => 'nullable|numeric',

        // Cloture 
        'slt_cours_amenager_clo' => 'nullavle|numeric',
        'occupantsCL' => 'required|array', 
        'occupantsCL.*.slt_cours_amenager_clo'  => 'nullable|string',  
        'occupantsCL.*.nbr_longueur_avant_clo'  => 'nullable|numeric', 
        'occupantsCL.*.slt_categorie_clo'       => 'nullable|string', 
        'occupantsCL.*.nbr_prix_metre_carre_clo'=> 'nullable|numeric', 
        'occupantsCL.*.nbr_coefficient_clo'     => 'nullable|numeric', 
        'occupantsCL.*.nbr_valeur_clo'          => 'nullable|numeric',

        // Amenagement
        'nbr_valeur_totale_ap'  => 'nullable|numeric', 
        'occupantsAP' => 'required|array', 
        'occupantsAP.*.txt_designation_am'    => 'nullable|string',
        'occupantsAP.*.nbr_valeur_unitaire_am'=> 'nullable|numeric', 
        'occupantsAP.*.nbr_quantile_am'       => 'nullable|numeric',
        'occupantsAP.*.slt_coeficien_am'      => 'nullable|numeric',
        'occupantsAP.*.nbr_valeur_am'         => 'nullable|numeric',

        // Evaluation Traitement  
        'nbr_surface' => 'nullable|numeric', 
        'nbr_valeurVenaleLimeuble' => 'nullable|numeric',
        'nbr_valeurLocative' => 'nullable|numeric',
        'dt_dateEvaluation' => 'nullable|date', 
        'txt_superficie_totale'  => 'nullable|numeric',
        'txt_superficie_bati_sol'  => 'nullable|numeric',
        'slt_secteur'   => 'nullable|string',
        'nbr_prix_metre_carre' => 'nullable|numeric',
        'nbr_valeur_terrain' => 'nullable|numeric', 

        ]); 
            $occupants = $request->input('occupants');
            $montantLoyerTotal = 0;
            foreach ($request->input('occupants') as $occupant) {
                $montantLoyerTotal += (float) $occupant['nbr_montantLoyerTG'];
            }

            // Calcul  TVA Total (18%)
            $TVATotal = $montantLoyerTotal * 0.18;

            foreach ($occupants as $occupant) {
                ReferenceUsage::create([
                    'txt_num_dossier'       => $validatedData['txt_num_dossier'], 
                    'txt_nicad'             => $validatedData['txt_nicad'], 
                    'slt_residence'         => $validatedData['slt_residence'],
                    'slt_usage'             => $validatedData['slt_usage'],

                    'txt_nomOccupantTG'     => $occupant['txt_nomOccupantTG'],
                    'txt_numAppartementTG'  => $occupant['txt_numAppartementTG'],
                    'txt_activiteTG'        => $occupant['txt_activiteTG'],
                    'txt_nineaTG'           => $occupant['txt_nineaTG'],
                    'tel_telephoneTG'       => $occupant['tel_telephoneTG'],
                    'nbr_montantLoyerTG'    => $occupant['nbr_montantLoyerTG'],
                    'txt_dateLieuNaissanceTG'    => $occupant['txt_dateLieuNaissanceTG'],
                    'txt_cniPasseportTG'    => $occupant['txt_cniPasseportTG'],
                    'dt_dateDelivranceTG'   => $occupant['dt_dateDelivranceTG'],

                    // Ajouter les calculs du total et de la TVA
                    'nbr_montantLoyerTotal' => $montantLoyerTotal,
                    'nbr_TVATotal' => $TVATotal,

                ]);
            }

            EvaluationTerrain::create([
                'txt_nicad'                 =>  $validatedData['txt_nicad'],
                'txt_num_dossier'           =>  $validatedData['txt_num_dossier'],  
                'nbr_surface'               =>  $validatedData['nbr_surface'],
                
                'txt_superficie_bati_sol'   =>  $validatedData['txt_superficie_bati_sol'],
                'slt_secteur'               =>  $validatedData['slt_secteur'],
                'nbr_prix_metre_carre'      =>  $validatedData['nbr_prix_metre_carre'],
                'nbr_valeur_terrain'        =>  $validatedData['nbr_valeur_terrain'], 
                'dt_dateEvaluation'         =>  $validatedData['dt_dateEvaluation'], 
                'nbr_valeurVenaleLimeuble'  =>  $validatedData['nbr_valeurVenaleLimeuble'],
                'nbr_valeurLocative'        =>  $validatedData['nbr_valeurLocative'],
            ]);
        
            // Bati
            $occupantsBP = $request->input('occupantsBP');
            $valeurTerrain_Bati = 0; 
            // Calculer la somme de la valeur pour les occupants
            foreach ($request->input('occupantsBP') as $occupant) {
                $valeurTerrain_Bati += (float) $occupant['nbr_valeurTG'];
            }

            // Créer les enregistrements EvaluationTerrain pour chaque occupant dans $occupantsBP
            foreach ($occupantsBP as $occupant) {

                EvaluationBati::create([
                    // Clés étrangères et autres champs
                    'txt_num_dossier'        => $validatedData['txt_num_dossier'], 
                    'txt_nicad'              => $validatedData['txt_nicad'], 

                    // Primaire 
                    'txt_dependant_domainePR'   =>  $validatedData['txt_dependant_domainePR'],
                    'slt_categoriePR'           => $validatedData['slt_categoriePR'],
                    'nbr_prix_metre_carrePR'    =>  $validatedData['nbr_prix_metre_carrePR'],
                    'nbr_surface_bati_solPR'    =>  $validatedData['nbr_surface_bati_solPR'],
                    'nbr_niveauPR'              =>  $validatedData['nbr_niveauPR'],
                    'nbr_surface_utilePR'       =>  $validatedData['nbr_surface_utilePR'],
                    'slt_coeffPR'               =>  $validatedData['slt_coeffPR'],
                    'nbr_surface_corrigerPR'    =>  $validatedData['nbr_surface_corrigerPR'],
                    'nbr_valeurPR'              =>  $validatedData['nbr_valeurPR'],

                    'currentCat' => $request->currentCat, 
                    'slt_categorieTG'        => $occupant['slt_categorieTG'] ?? null,
                    'txt_valeur_terrain_bati'=> $valeurTerrain_Bati,
                    'slt_dependant_domaine'  => $occupant['slt_dependant_domaineTG'] ?? null,
                    'nbr_prix_metre_carreTG' => $occupant['nbr_prix_metre_carreTG'] ?? null,
                    'nbr_surface_bati_solTG' => $occupant['nbr_surface_bati_solTG'] ?? null,
                    'nbr_niveauTG'           => $occupant['nbr_niveauTG'] ?? null,
                    'nbr_surface_utileTG'    => $occupant['nbr_surface_utileTG'] ?? null,
                    'slt_coeffTG'            => $occupant['slt_coeffTG'] ?? null,
                    'nbr_surface_corrigerTG' => $occupant['nbr_surface_corrigerTG'] ?? null,
                    'nbr_valeurTG'           => $occupant['nbr_valeurTG'] ?? null,
                ]);
            }
 
            // Cours Aménager
            $occupantsCA = $request->input('occupantsCA');
            $valeurCours_Amenager = 0; 
            // somme occupants
            foreach ($request->input('occupantsCA') as $occupant) {
                $valeurCours_Amenager += (float) $occupant['nbr_valeur_ca_total'];
            }
            foreach ($occupantsCA as $occupant) {
                EvaluationCoursAmenagee::create([
                    // Clés étrangères
                    'txt_num_dossier'           => $validatedData['txt_num_dossier'], 
                    'txt_nicad'                 => $validatedData['txt_nicad'], 

                    'nbr_valeur_total_ca'       => $valeurCours_Amenager,
                    'slt_cours_amenager_totale' => $occupant['slt_cours_amenager_totale'],
                    'nbr_surface_ca_total'      => $occupant['nbr_surface_ca_total'],
                    'slt_categorie_ca_total'    => $occupant['slt_categorie_ca_total'],
                    'nbr_prix_metre_carre_ca_total' => $occupant['nbr_prix_metre_carre_ca_total'],
                    'nbr_coefficient_ca_total'  => $occupant['nbr_coefficient_ca_total'],
                    'nbr_valeur_ca_total'       => $occupant['nbr_valeur_ca_total'], 
                ]);
            }

            // Clôture
            $occupantsCL = $request->input('occupantsCL'); 
            $valeurCloture = 0; 
            // somme occupants
            foreach ($request->input('occupantsCL') as $occupant) {
                $valeurCloture += (float) $occupant['nbr_valeur_clo'];
            }
            foreach ($occupantsCL as $occupant) {
                EvaluationCloture::create([
                    // Clés étrangères 
                    'txt_num_dossier'           => $validatedData['txt_num_dossier'],
                    'txt_nicad'                 => $validatedData['txt_nicad'],
                    'nbr_valeur_total_clotur'   => $valeurCloture ,

                    'slt_cours_amenager_clo'    => $occupant['slt_cours_amenager_clo'] ?? null,
                    'nbr_longueur_avant_clo'    => $occupant['nbr_longueur_avant_clo'] ?? null,
                    'slt_categorie_clo'         => $occupant['slt_categorie_clo'] ?? null,
                    'nbr_prix_metre_carre_clo'  => $occupant['nbr_prix_metre_carre_clo'] ?? null,
                    'nbr_coefficient_clo'       => $occupant['nbr_coefficient_clo'] ?? null,
                    'nbr_valeur_clo'            => $occupant['nbr_valeur_clo'] ?? null,
                ]);

            }

            // Amenagement
            $occupantsAP = $request->input('occupantsAP');
            $valeurAmenagement = 0; 
            // somme occupants
            foreach ($request->input('occupantsAP') as $occupant) {
                $valeurAmenagement += (float) $occupant['nbr_valeur_am'];
            }
            foreach ($occupantsAP as $occupant) { 
                EvaluationAmenagement::create([
                    'txt_num_dossier'       => $validatedData['txt_num_dossier'],
                    'txt_nicad'             => $validatedData['txt_nicad'], 

                    'nbr_valeur_totale_ap'  => $valeurAmenagement,  
                    'txt_designation_am'    => $occupant['txt_designation_am'] ?? null,
                    'nbr_valeur_unitaire_am' => $occupant['nbr_valeur_unitaire_am'] ?? null,
                    'nbr_quantile_am'       => $occupant['nbr_quantile_am'] ?? null,
                    'slt_coeficien_am'      => $occupant['slt_coeficien_am'] ?? null,
                    'nbr_valeur_am'         => $occupant['nbr_valeur_am'] ?? null,
                ]);
            }
            
            return redirect()->back()->with('success', 'Donnée enregistrée !');
        

    }


}
