<?php

namespace App\Http\Controllers;

use App\Models\Dossier;
use App\Models\EvaluationAmenagement;
use App\Models\EvaluationBati;
use App\Models\EvaluationCloture;
use App\Models\EvaluationCoursAmenagee;
use App\Models\EvaluationTerrain;
use App\Models\ReferenceUsage;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;

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

    public function search(Request $request)
    {
        // Récupérer le numéro de dossier depuis la requête
        $numero = $request->input('numero');
    
        // Chercher le dossier dans la base de données
        $dossier = Dossier::where('txt_num_dossier', $numero)->first();
    
        // Si le dossier existe, retourner les informations du dossier
        if ($dossier) {
            return response()->json([
                'dossier' => $dossier,
                'message' => 'Dossier trouvé !',
                'messageType' => 'success'  // Type de message pour l'alerte
            ]);
        } else {
            // Sinon, retourner un message d'erreur
            return response()->json([
                'message' => "Numéro de dossier n'est pas enregistré.",
                'messageType' => 'danger'  // Type de message pour l'alerte
            ]);
        }
    }
     // PAGES GEOMETRE
    public function store(Request $request)
    {
         $validatedData = $request->validate([
        //table Reference_usage
        'slt_reference_usage' => 'nullable|string',
        'txt_occupan_habitaion_1' => 'nullable|string',
        'txt_activite_principal_hbt_1' => 'nullable|string',
        'txt_ninea_occupan_hbt_1' => 'nullable|string',
        'tel_tel_occupant_hbt_1' => 'nullable|numeric',
        'nbr_montant_loyer_hbt_1' => 'nullable|integer',
        'txt_activite_commercial' => 'nullable|string',
        'txt_occopan_commercial' => 'nullable|string',
        'txt_activite_industriel' => 'nullable|string',
        'txt_occopan_industriel' => 'nullable|string',
        'txt_activite_agricole' => 'nullable|string',
        'txt_occopan_agricole' => 'nullable|string',
        'txt_activite_professionnelle' => 'nullable|string',
        'txt_occopan_professionnelle' => 'nullable|string',
        'txt_activite_culte' => 'nullable|string',
        'txt_occopan_culte' => 'nullable|string',
        'txt_Activite_administratif' => 'nullable|string',
        'txt_occupan_administratif' => 'nullable|string',

        // table evaluation_terrains
        'txt_date_devaluation' => 'nullable|date',
        'txt_superficie_totale' => 'nullable|integer',
        'txt_superficie_bati_sol' => 'nullable|integer',
        'slt_secteur' => 'nullable|string',
        'nbr_prix_metre_carré' => 'nullable|integer',
        'nbr_valeur_terrain' => 'nullable|integer',

        // table Evaluation_batis
        'slt_type_residence' => 'nullable|string',
        'rd_type_maissons' => 'nullable|string',
        'chk_bati_principal' => 'nullable|boolean',
        'slt_cat' => 'nullable|string',
        'nbr_prix_metre_carre' => 'nullable|integer',
        'nbr_surface_bati_sol' => 'nullable|integer',
        'nbr_niveau' => 'nullable|integer',
        'nbr_surface_brute' => 'nullable|integer',
        'nbr_surface_utile' => 'nullable|integer',
        'slt_coeff' => 'nullable|string',
        'nbr_surface_corriger' => 'nullable|integer',
        'nbr_valeur' => 'nullable|integer',
        'txt_valeur_terrain_bati' => 'nullable|integer',

        // table evaluation_cours_amenagees
        'chk_cours_amenager_totale' => 'nullable|boolean',
        'nbr_surface_ca_total' => 'nullable|string',
        'slt_categorie_ca_total' => 'nullable|string',
        'nbr_prix_metre_carre_ca_total' => 'nullable|string',       //default(false),
        'nbr_coefficient_ca_total' => 'nullable|string',
        'nbr_valeur_ca_total' => 'nullable|integer',
        'nbr_valeur_total_cours' => 'nullable|integer',

        //table Evaluation_cloture
        'chk_perimetre_cloture' => 'nullable|boolean',
        'nbr_longueur_avant_clo' => 'nullable|string',
        'slt_categorie_clo' => 'nullable|string',
        'nbr_prix_metre_carre_clo' => 'nullable|boolean',
        'nbr_coefficient_clo' => 'nullable|string',
        'nbr_valeur_clo' => 'nullable|integer',
        'nbr_valeur_total_clotur' => 'nullable|integer',

        // table Evalution_amenagement
        'txt_designation_am' => 'nullable|string',
        'nbr_valeur_unitaire_am' => 'nullable|string',
        'nbr_quantile_am' => 'nullable|string',
        'slt_coeficien_am' => 'nullable|boolean',
        'nbr_valeur_am' => 'nullable|string',
        'nbr_valeur_totale_ap' => 'nullable|integer',



        ]);

            ReferenceUsage::create([
                'slt_reference_usage' => $validatedData['slt_reference_usage'] ?? null,
                'txt_occupan_habitaion_1' => $validatedData['txt_occupan_habitaion_1'] ?? null,
                'txt_activite_principal_hbt_1' => $validatedData['txt_activite_principal_hbt_1'] ?? null,
                'txt_ninea_occupan_hbt_1' => $validatedData['txt_ninea_occupan_hbt_1'] ?? null,
                'tel_tel_occupant_hbt_1' => $validatedData['tel_tel_occupant_hbt_1'] ?? null,
                'nbr_montant_loyer_hbt_1' => $validatedData['nbr_montant_loyer_hbt_1'] ?? null,
                'txt_activite_commercial' => $validatedData['txt_activite_commercial'] ?? null,
                'txt_occopan_commercial' => $validatedData['txt_occopan_commercial'] ?? null,
                'txt_activite_industriel' => $validatedData['txt_activite_industriel'] ?? null,
                'txt_occopan_industriel' => $validatedData['txt_occopan_industriel'] ?? null,
                'txt_activite_agricole' => $validatedData['txt_activite_agricole'] ?? null,
                'txt_occopan_agricole' => $validatedData['txt_occopan_agricole'] ?? null,
                'txt_activite_professionnelle' => $validatedData['txt_activite_professionnelle'] ?? null,
                'txt_occopan_professionnelle' => $validatedData['txt_occopan_professionnelle'] ?? null,
                'txt_activite_culte' => $validatedData['txt_activite_culte'] ?? null,
                'txt_occopan_culte' => $validatedData['txt_occopan_culte'] ?? null,
                'txt_Activite_administratif' => $validatedData['txt_Activite_administratif'] ?? null,
                'txt_occupan_administratif' => $validatedData['txt_occupan_administratif'] ?? null,
            ]);

            // table evaluation_terrains
            EvaluationTerrain::create([
                'txt_date_devaluation' => $validatedData['txt_date_devaluation'] ?? null,
                'txt_superficie_totale' => $validatedData['txt_superficie_totale'] ?? null,
                'txt_superficie_bati_sol' => $validatedData['txt_superficie_bati_sol'] ?? null,
                'slt_secteur' => $validatedData['slt_secteur'],
                'nbr_prix_metre_carré' => $validatedData['nbr_prix_metre_carré'] ?? null,
                'nbr_valeur_terrain' => $validatedData['nbr_valeur_terrain'] ?? null,
            ]);


            // table Evaluation_batis
            EvaluationBati::create([
                'slt_type_residence' => $validatedData['slt_type_residence'] ?? null,
                'rd_type_maissons' => $validatedData['rd_type_maissons'] ?? null,
                'chk_bati_principal' => filter_var($validatedData['chk_bati_principal'] ?? false, FILTER_VALIDATE_BOOLEAN),
                'slt_cat' => $validatedData['slt_cat'],
                'nbr_prix_metre_carre' => $validatedData['nbr_prix_metre_carre'] ?? null,
                'nbr_surface_bati_sol' => $validatedData['nbr_surface_bati_sol'] ?? null,
                'nbr_niveau' => $validatedData['nbr_niveau'] ?? null,
                'nbr_surface_brute' => $validatedData['nbr_surface_brute'] ?? null,
                'nbr_surface_utile' => $validatedData['nbr_surface_utile'] ?? null,
                'slt_coeff' => $validatedData['slt_coeff'] ?? null,
                'nbr_surface_corriger' => $validatedData['nbr_surface_corriger'] ?? null,
                'nbr_valeur' => $validatedData['nbr_valeur'] ?? null,
                'txt_valeur_terrain_bati' => $validatedData['txt_valeur_terrain_bati'] ?? null,
            ]);


            // table evaluation_cours_amenagees
            EvaluationCoursAmenagee::create([
                'chk_cours_amenager_totale' => $validatedData['chk_cours_amenager_totale'] ?? null,
                'nbr_surface_ca_total' => $validatedData['nbr_surface_ca_total'] ?? null,
                'slt_categorie_ca_total' => $validatedData['slt_categorie_ca_total'],
                'nbr_prix_metre_carre_ca_total' => $validatedData['nbr_prix_metre_carre_ca_total'] ?? null, //default(false),
                'nbr_coefficient_ca_total' => $validatedData['nbr_coefficient_ca_total'] ?? null,
                'nbr_valeur_ca_total' => $validatedData['nbr_valeur_ca_total'] ?? null,
                'nbr_valeur_total_cours' => $validatedData['nbr_valeur_total_cours'] ?? null,
            ]);

            //table Evaluation_cloture
            EvaluationCloture::create([
                'chk_perimetre_cloture' => $validatedData['chk_perimetre_cloture'] ?? null,
                'nbr_longueur_avant_clo' => $validatedData['nbr_longueur_avant_clo'] ?? null,
                'slt_categorie_clo' => $validatedData['slt_categorie_clo'] ?? null,
                'nbr_prix_metre_carre_clo' => $validatedData['nbr_prix_metre_carre_clo'] ?? null,
                'nbr_coefficient_clo' => $validatedData['nbr_coefficient_clo'] ?? null,
                'nbr_valeur_clo' => $validatedData['nbr_valeur_clo'] ?? null,
                'nbr_valeur_total_clotur' => $validatedData['nbr_valeur_total_clotur'] ?? null,
            ]);

            // table Evalution_amenagement
            EvaluationAmenagement::create([
                'txt_designation_am' => $validatedData['txt_designation_am'] ?? null,
                'nbr_valeur_unitaire_am' => $validatedData['nbr_valeur_unitaire_am'] ?? null,
                'nbr_quantile_am' => $validatedData['nbr_quantile_am'] ?? null,
                'slt_coeficien_am' => $validatedData['slt_coeficien_am'] ?? null,
                'nbr_valeur_am' => $validatedData['nbr_valeur_am'] ?? null,
                'nbr_valeur_totale_ap' => $validatedData['nbr_valeur_totale_ap'] ?? null,
            ]);

            
            return redirect()->back()->with('success', 'Donnée enregistrée !');
        

    }
}
