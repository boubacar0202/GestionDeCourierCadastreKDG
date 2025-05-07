<?php

namespace App\Http\Controllers;

use App\Models\Dossier;
use App\Models\EvaluationAmenagement;
use App\Models\EvaluationBati;
use App\Models\EvaluationCloture;
use App\Models\EvaluationCoursAmenagee;
use App\Models\EvaluationTerrain;
use App\Models\ReferenceUsage;
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

        $terrain = Terrain::where('txt_nicad', session('txt_nicad'))->first();

        return Inertia::render('geometre/create', [
            'terrain' => $terrain,
            'txt_nicad' => $terrain?->txt_nicad,
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
        dd($request->all());

         $validatedData = $request->validate([
        //table Reference_usage
        'txt_num_dossier' => 'required|string|exists:dossiers,txt_num_dossier',
        'txt_nicad' => 'required|exists:terrains,txt_nicad',
        // 'txt_nicad' => 'required|string',

        'slt_residence' => 'nullable|string',
        'slt_usage' => 'nullable|string',
        // usage
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
        'txt_valeur_terrain_bati' =>'nullable|numeric',
        'occupantsBP' => 'required|array',
        'occupantsBP.*.slt_dependant_domaineTG' => 'nullable|string',
        'occupantsBP.*.nbr_prix_metre_carreTG' => 'nullable|numeric',
        'occupantsBP.*.nbr_surface_bati_solTG' => 'nullable|numeric',
        'occupantsBP.*.nbr_niveauTG' => 'nullable|numeric',
        'occupantsBP.*.nbr_surface_utileTG' => 'nullable|numeric',
        'occupantsBP.*.slt_coeffTG' => 'nullable|numeric',
        'occupantsBP.*.nbr_surface_corrigerTG' => 'nullable|numeric',
        'occupantsBP.*.nbr_valeurTG' => 'nullable|numeric',



        // table evaluation_terrains
        // 'txt_date_devaluation' => 'nullable|date',
        // 'txt_superficie_totale' => 'nullable|integer',
        // 'txt_superficie_bati_sol' => 'nullable|integer',
        // 'slt_secteur' => 'nullable|string',
        // 'nbr_prix_metre_carre' => 'nullable|integer',
        // 'nbr_valeur_terrain' => 'nullable|integer',

        // // table Evaluation_batis
        // 'slt_type_residence' => 'nullable|string',
        // 'rd_type_maissons' => 'nullable|string',
        // 'chk_bati_principal' => 'nullable|boolean',
        // 'slt_cat' => 'nullable|string',
        // 'nbr_prix_metre_carre' => 'nullable|integer',
        // 'nbr_surface_bati_sol' => 'nullable|integer',
        // 'nbr_niveau' => 'nullable|integer',
        // 'nbr_surface_brute' => 'nullable|integer',
        // 'nbr_surface_utile' => 'nullable|integer',
        // 'slt_coeff' => 'nullable|string',
        // 'nbr_surface_corriger' => 'nullable|integer',
        // 'nbr_valeur' => 'nullable|integer',
        // 'txt_valeur_terrain_bati' => 'nullable|integer',

        // // table evaluation_cours_amenagees
        // 'chk_cours_amenager_totale' => 'nullable|boolean',
        // 'nbr_surface_ca_total' => 'nullable|string',
        // 'slt_categorie_ca_total' => 'nullable|string',
        // 'nbr_prix_metre_carre_ca_total' => 'nullable|string',       //default(false),
        // 'nbr_coefficient_ca_total' => 'nullable|string',
        // 'nbr_valeur_ca_total' => 'nullable|integer',
        // 'nbr_valeur_total_cours' => 'nullable|integer',

        // //table Evaluation_cloture
        // 'chk_perimetre_cloture' => 'nullable|boolean',
        // 'nbr_longueur_avant_clo' => 'nullable|string',
        // 'slt_categorie_clo' => 'nullable|string',
        // 'nbr_prix_metre_carre_clo' => 'nullable|boolean',
        // 'nbr_coefficient_clo' => 'nullable|string',
        // 'nbr_valeur_clo' => 'nullable|integer',
        // 'nbr_valeur_total_clotur' => 'nullable|integer',

        // // table Evalution_amenagement
        // 'txt_designation_am' => 'nullable|string',
        // 'nbr_valeur_unitaire_am' => 'nullable|string',
        // 'nbr_quantile_am' => 'nullable|string',
        // 'slt_coeficien_am' => 'nullable|boolean',
        // 'nbr_valeur_am' => 'nullable|string',
        // 'nbr_valeur_totale_ap' => 'nullable|integer',



        ]);

            $occupants = $request->input('occupants');
            $montantLoyerTotal = 0;
            foreach ($request->input('occupants') as $occupant) {
                $montantLoyerTotal += (float) $occupant['nbr_montantLoyerTG'];
            }
        
            // Calcul de la TVA Total (18%)
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

            $occupantsBP = $request->input('occupantsBP');
            $valeurTerrain_Bati = 0;
            foreach ($request->input('occupants') as $occupant) {
                $valeurTerrain_Bati += (float) $occupant['nbr_valeurTG'];
            }
            foreach ($occupantsBP as $occupant) {
                EvaluationTerrain::create([
                    // clé etrangère
                    'txt_num_dossier'       => $validatedData['txt_num_dossier'], 
                    'txt_nicad'             => $validatedData['txt_nicad'], 

                    'slt_dependant_domaine'     => $occupant['slt_dependant_domaineTG'] ?? null,
                    'nbr_prix_metre_carreTG'    => $occupant['nbr_prix_metre_carreTG'] ?? null,
                    'nbr_surface_bati_solTG'    => $occupant['nbr_surface_bati_solTG'] ?? null,
                    'nbr_niveauTG'              => $occupant['nbr_niveauTG'] ?? null,
                    'nbr_surface_utileTG'       => $occupant['nbr_surface_utileTG'] ?? null,
                    'slt_coeffTG'               => $occupant['slt_coeffTG'] ?? null,
                    'nbr_surface_corrigerTG'    => $occupant['nbr_surface_corrigerTG'] ?? null,
                    'nbr_valeurTG'              => $occupant['nbr_valeurTG'] ?? null,

                    'txt_valeur_terrain_bati' => $valeurTerrain_Bati,
                ]);
            }
        
            // // table evaluation_terrains
            // EvaluationTerrain::create([
            //     'txt_date_devaluation'      => $validatedData['txt_date_devaluation'] ?? null,
            //     'txt_superficie_totale'     => $validatedData['txt_superficie_totale'] ?? null,
            //     'txt_superficie_bati_sol'   => $validatedData['txt_superficie_bati_sol'] ?? null,
            //     'slt_secteur'               => $validatedData['slt_secteur'],
            //     'nbr_prix_metre_carre'      => $validatedData['nbr_prix_metre_carre'] ?? null,
            //     'nbr_valeur_terrain'        => $validatedData['nbr_valeur_terrain'] ?? null,
            // ]);

            // // table Evaluation_batis
            // EvaluationBati::create([
            //     'slt_type_residence' => $validatedData['slt_type_residence'] ?? null,
            //     'rd_type_maissons' => $validatedData['rd_type_maissons'] ?? null,
            //     'chk_bati_principal' => filter_var($validatedData['chk_bati_principal'] ?? false, FILTER_VALIDATE_BOOLEAN),
            //     'slt_cat' => $validatedData['slt_cat'],
            //     'nbr_prix_metre_carre' => $validatedData['nbr_prix_metre_carre'] ?? null,
            //     'nbr_surface_bati_sol' => $validatedData['nbr_surface_bati_sol'] ?? null,
            //     'nbr_niveau' => $validatedData['nbr_niveau'] ?? null,
            //     'nbr_surface_brute' => $validatedData['nbr_surface_brute'] ?? null,
            //     'nbr_surface_utile' => $validatedData['nbr_surface_utile'] ?? null,
            //     'slt_coeff' => $validatedData['slt_coeff'] ?? null,
            //     'nbr_surface_corriger' => $validatedData['nbr_surface_corriger'] ?? null,
            //     'nbr_valeur' => $validatedData['nbr_valeur'] ?? null,
            //     'txt_valeur_terrain_bati' => $validatedData['txt_valeur_terrain_bati'] ?? null,
            // ]);


            // // table evaluation_cours_amenagees
            // EvaluationCoursAmenagee::create([
            //     'chk_cours_amenager_totale' => $validatedData['chk_cours_amenager_totale'] ?? null,
            //     'nbr_surface_ca_total' => $validatedData['nbr_surface_ca_total'] ?? null,
            //     'slt_categorie_ca_total' => $validatedData['slt_categorie_ca_total'],
            //     'nbr_prix_metre_carre_ca_total' => $validatedData['nbr_prix_metre_carre_ca_total'] ?? null, //default(false),
            //     'nbr_coefficient_ca_total' => $validatedData['nbr_coefficient_ca_total'] ?? null,
            //     'nbr_valeur_ca_total' => $validatedData['nbr_valeur_ca_total'] ?? null,
            //     'nbr_valeur_total_cours' => $validatedData['nbr_valeur_total_cours'] ?? null,
            // ]);

            // //table Evaluation_cloture
            // EvaluationCloture::create([
            //     'chk_perimetre_cloture' => $validatedData['chk_perimetre_cloture'] ?? null,
            //     'nbr_longueur_avant_clo' => $validatedData['nbr_longueur_avant_clo'] ?? null,
            //     'slt_categorie_clo' => $validatedData['slt_categorie_clo'] ?? null,
            //     'nbr_prix_metre_carre_clo' => $validatedData['nbr_prix_metre_carre_clo'] ?? null,
            //     'nbr_coefficient_clo' => $validatedData['nbr_coefficient_clo'] ?? null,
            //     'nbr_valeur_clo' => $validatedData['nbr_valeur_clo'] ?? null,
            //     'nbr_valeur_total_clotur' => $validatedData['nbr_valeur_total_clotur'] ?? null,
            // ]);

            // // table Evalution_amenagement
            // EvaluationAmenagement::create([
            //     'txt_designation_am' => $validatedData['txt_designation_am'] ?? null,
            //     'nbr_valeur_unitaire_am' => $validatedData['nbr_valeur_unitaire_am'] ?? null,
            //     'nbr_quantile_am' => $validatedData['nbr_quantile_am'] ?? null,
            //     'slt_coeficien_am' => $validatedData['slt_coeficien_am'] ?? null,
            //     'nbr_valeur_am' => $validatedData['nbr_valeur_am'] ?? null,
            //     'nbr_valeur_totale_ap' => $validatedData['nbr_valeur_totale_ap'] ?? null,
            // ]);

            
            return redirect()->back()->with('success', 'Donnée enregistrée !');
        

    }
}
