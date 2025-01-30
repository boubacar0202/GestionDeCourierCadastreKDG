<?php

namespace App\Http\Controllers;

use App\Models\Arrondissement;
use App\Models\Commune;
use App\Models\Departement;
use App\Models\Donnee;
use App\Models\EvaluationAmenagement;
use App\Models\EvaluationBati;
use App\Models\EvaluationCloture;
use App\Models\EvaluationCoursAmenagee;
use App\Models\EvaluationTerrain;
use App\Models\Region;
use App\Models\Dossier;
use App\Models\ReferenceCadastrale;
use App\Models\ReferenceUsage;
use App\Models\Terrain;
use App\Models\Titulaire;
use Illuminate\Foundation\Testing\Concerns\InteractsWithAuthentication;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;


class SecretariatController extends Controller
{
    //
    public function index()
    {
        $donnee = Donnee::all();

        return Inertia::render("secretariat/index",  ["donnees" => $donnee]);
    }

    public function create()
    {
        $regions = Region::all();

        return Inertia::render("secretariat/create",  [
            "regions" => $regions,
            "departements" => [],
        ]);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'numDossier' => 'required|string|min:1|max:10|unique:dossiers,numDossier',
            'numDordre' => 'required|integer|min:1|max:255',
            'serviceRendu' => 'required|string|min:1|max:255',
            'etatCession' => 'required|string|min:1|max:255',
            'cessionDefinitive' => 'required|string|min:1|max:255',
            'dateCreation' => 'required|date',

            //table Titulaire_terrain (table association entre Titulaire <=> Terrain)
            //'titulaire_id'
            //'terrain_id'

            // Table region
            'regions' => 'required|string',

            // Table departement
            'departements' => 'required|string',

            // Table arrondissement
            'arrondissements' => 'required|string',

            // Table commune
            'communes' => 'required|string',

            //table Terrain
            'txt_lotissement' => 'nullable|string|min:1|max:255',
            'txt_num_lotissement' => 'nullable|string',
            'txt_num_section' => 'nullable|string',
            'txt_num_parcelle' => 'nullable|string',
            'txt_num_titre' => 'nullable|string',
            'nbr_surface' => 'nullable|integer',
            'slt_document_admin' => 'nullable|string',
            'txt_num_deliberation' => 'nullable|string',
            'dt_date_deliberation' => 'nullable|date',
            'txt_nicad' => ['nullable', 'string', 'max:25', 'unique:terrains,txt_nicad'],

            // table Reference_Cadastral
            'rd_immatriculation_terrain' => 'nullable|boolean',
            'slt_dependant_domaine' => 'nullable|string',
            'slt_bornage' => 'nullable|string',
            'ussu_bornage' => 'nullable|string',
            'txt_titre_mere' => 'nullable|string',
            'slt_lf' => 'nullable|string',
            'txt_num_requisition' => 'nullable|string',
            'txt_surface_bornage' => 'nullable|string',
            'dt_date_bornage' => 'nullable|date',
            'txt_nom_geometre' => 'nullable|string',

            // table Titulaire
            'slt_titulaire' => 'required|string|min:1|max:255',
            'txt_nationalite' => 'required|string|min:1|max:255',
            'slt_civilite' => 'required|string|min:1|max:255',
            'txt_prenom' => 'required|string|min:1|max:255',
            'txt_nom' => 'required|string|min:1|max:255',
            'slt_piece' => 'required|string|min:1|max:255',
            'txt_numPiece' => 'required|string|min:1|max:255',
            'dt_date_delivrance' => 'required|date',
            'dt_date_naissance' => 'required|date',
            'txt_lieu_naissance' => 'required|string|min:1|max:255',
            'txt_adresse' => 'required|string|min:1|max:255',
            'tel_telephone' => 'required|numeric',
            'txt_ninea' => 'nullable|string',
            'eml_email' => 'nullable|string',
            'txt_representant' => 'nullable|string',
            'txt_telRepresentant' => 'nullable|numeric',

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

        DB::beginTransaction();
        try {

            $dossiers = Dossier::create([
                'numDossier' => $validatedData['numDossier'],
                'numDordre' => $validatedData['numDordre'],
                'serviceRendu' => $validatedData['serviceRendu'],
                'etatCession' => $validatedData['etatCession'],
                'cessionDefinitive' => $validatedData['cessionDefinitive'],
                'dateCreation' => $validatedData['dateCreation'],
            ]);


            //table Terrain
            $terrains = Terrain::create([
                'txt_lotissement' => $validatedData['txt_lotissement'] ?? null,
                'txt_num_lotissement' => $validatedData['txt_num_lotissement'] ?? null,
                'txt_num_section' => $validatedData['txt_num_section'] ?? null,
                'txt_num_parcelle' => $validatedData['txt_num_parcelle'] ?? null,
                'txt_num_titre' => $validatedData['txt_num_titre'] ?? null,
                'nbr_surface' => $validatedData['nbr_surface'] ?? null,
                'slt_document_admin' => $validatedData['slt_document_admin'] ?? null,
                'txt_num_deliberation' => $validatedData['txt_num_deliberation'] ?? null,
                'dt_date_deliberation' => $validatedData['dt_date_deliberation'] ?? null,
                'txt_nicad' => $validatedData['txt_nicad'] ?? null,
            ]);

            // table Reference_Cadastral
            $referenceCadastrales = ReferenceCadastrale::create([
                'rd_immatriculation_terrain' => $validatedData['rd_immatriculation_terrain'] ?? null,
                'slt_dependant_domaine' => $validatedData['slt_dependant_domaine'] ?? null,
                'slt_bornage' => $validatedData['slt_bornage'] ?? null,
                'ussu_bornage' => $validatedData['ussu_bornage'] ?? null,
                'txt_titre_mere' => $validatedData['txt_titre_mere'] ?? null,
                'slt_lf' => $validatedData['slt_lf'] ?? null,
                'txt_num_requisition' => $validatedData['txt_num_requisition'] ?? null,
                'txt_surface_bornage' => $validatedData['txt_surface_bornage'] ?? null,
                'dt_date_bornage' => $validatedData['dt_date_bornage'] ?? null,
                'txt_nom_geometre' => $validatedData['txt_nom_geometre'] ?? null,
            ]);

            // table Titulaire
            $titulaires = Titulaire::create([
                'slt_titulaire' => $validatedData['slt_titulaire'],
                'txt_nationalite' => $validatedData['txt_nationalite'],
                'slt_civilite' => $validatedData['slt_civilite'],
                'txt_prenom' => $validatedData['txt_prenom'],
                'txt_nom' => $validatedData['txt_nom'],
                'slt_piece' => $validatedData['slt_piece'],
                'txt_numPiece' => $validatedData['txt_numPiece'],
                'dt_date_delivrance' => $validatedData['dt_date_delivrance'],
                'dt_date_naissance' => $validatedData['dt_date_naissance'],
                'txt_lieu_naissance' => $validatedData['txt_lieu_naissance'],
                'txt_adresse' => $validatedData['txt_adresse'],
                'tel_telephone' => $validatedData['tel_telephone'],
                'txt_ninea' => $validatedData['txt_ninea'] ?? null,
                'eml_email' => $validatedData['eml_email'] ?? null,
                'txt_representant' => $validatedData['txt_representant'] ?? null,
                'tel_representant' => $validatedData['tel_representant'],
            ]);


            // PAGES GEOMETRE


            //table Reference_usage
            $referenceUsages = ReferenceUsage::create([
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
            $evaluationTerrains = EvaluationTerrain::create([
                'txt_date_devaluation' => $validatedData['txt_date_devaluation'] ?? null,
                'txt_superficie_totale' => $validatedData['txt_superficie_totale'] ?? null,
                'txt_superficie_bati_sol' => $validatedData['txt_superficie_bati_sol'] ?? null,
                'slt_secteur' => $validatedData['slt_secteur'],
                'nbr_prix_metre_carré' => $validatedData['nbr_prix_metre_carré'] ?? null,
                'nbr_valeur_terrain' => $validatedData['nbr_valeur_terrain'] ?? null,
            ]);


            // table Evaluation_batis
            $evaluationBatis = EvaluationBati::create([
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
            $evaluationCoursAmenagees = EvaluationCoursAmenagee::create([
                'chk_cours_amenager_totale' => $validatedData['chk_cours_amenager_totale'] ?? null,
                'nbr_surface_ca_total' => $validatedData['nbr_surface_ca_total'] ?? null,
                'slt_categorie_ca_total' => $validatedData['slt_categorie_ca_total'],
                'nbr_prix_metre_carre_ca_total' => $validatedData['nbr_prix_metre_carre_ca_total'] ?? null, //default(false),
                'nbr_coefficient_ca_total' => $validatedData['nbr_coefficient_ca_total'] ?? null,
                'nbr_valeur_ca_total' => $validatedData['nbr_valeur_ca_total'] ?? null,
                'nbr_valeur_total_cours' => $validatedData['nbr_valeur_total_cours'] ?? null,
            ]);

            //table Evaluation_cloture
            $evaluationClotures = EvaluationCloture::create([
                'chk_perimetre_cloture' => $validatedData['chk_perimetre_cloture'] ?? null,
                'nbr_longueur_avant_clo' => $validatedData['nbr_longueur_avant_clo'] ?? null,
                'slt_categorie_clo' => $validatedData['slt_categorie_clo'] ?? null,
                'nbr_prix_metre_carre_clo' => $validatedData['nbr_prix_metre_carre_clo'] ?? null,
                'nbr_coefficient_clo' => $validatedData['nbr_coefficient_clo'] ?? null,
                'nbr_valeur_clo' => $validatedData['nbr_valeur_clo'] ?? null,
                'nbr_valeur_total_clotur' => $validatedData['nbr_valeur_total_clotur'] ?? null,
            ]);

            // table Evalution_amenagement
            $evaluationAmenagements = EvaluationAmenagement::create([
                'txt_designation_am' => $validatedData['txt_designation_am'] ?? null,
                'nbr_valeur_unitaire_am' => $validatedData['nbr_valeur_unitaire_am'] ?? null,
                'nbr_quantile_am' => $validatedData['nbr_quantile_am'] ?? null,
                'slt_coeficien_am' => $validatedData['slt_coeficien_am'] ?? null,
                'nbr_valeur_am' => $validatedData['nbr_valeur_am'] ?? null,
                'nbr_valeur_totale_ap' => $validatedData['nbr_valeur_totale_ap'] ?? null,
            ]);

            $regions = Region::create([
                'region' => $validatedData['region'],
            ]);

            $departements = Departement::create([
                'departement' => $validatedData['departement'],
            ]);

            $arrondissements = Arrondissement::create([
                'arrondissement' => $validatedData['arrondissement'],
            ]);

            $communes = Commune::create([
                'commune' => $validatedData['commune'],
            ]);

            // Association entre le titulaire et le terrain
            $terrains->dossier()->associate($dossiers);
            $terrains->region()->associate($regions);
            $terrains->departement()->associate($departements);
            $terrains->arrondissement()->associate($arrondissements);
            $terrains->commune()->associate($communes);
            $evaluationTerrains->evaluationTerrain()->associate($evaluationTerrains);
            $evaluationBatis->evaluationBati()->associate($evaluationBatis);
            $evaluationClotures->evaluationCloture()->associate($evaluationClotures);
            $evaluationAmenagements->evaluationAmenagement()->associate($evaluationAmenagements);
            $evaluationCoursAmenagees->evaluationCoursAmenagee()->associate($evaluationCoursAmenagees);
            $terrains->save();
            $dossiers->save();
            $regions->save();
            $departements->save();
            $arrondissements->save();
            $communes->save();
            $referenceCadastrales->save();
            $titulaires->save();
            $referenceUsages->save();
            $evaluationBatis->save();
            $evaluationTerrains->save();
            $evaluationBatis->save();
            $evaluationAmenagements->save();
            $evaluationClotures->save();
            $evaluationCoursAmenagees->save();

            DB::commit();
            return redirect()->route('secretariat.create')->with('success', 'Dossier enregistré avec succès.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->withErrors(['error' => 'Erreur lors de l\'enregistrement.']);
        }
    }
}
