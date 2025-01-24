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
        // $departements = Departement::all();
        // $arrondissements = Arrondissement::all();
        // $communes = Commune::all();

        return Inertia::render("secretariat/create",  [
            "regions" => $regions,
            "departements" => [],
            // "arrondissements" => $arrondissements,
            // "communes" => $communes,
        ]);
    }

    
    public function store(Request $request){
        // validation Dosssier
        $request->validate([

            // table Dossier
            'txt_num_dossier' => 'required|unique|string|min:1|max:10',
            'txt_num_dordre' => 'required|unique',
            'slt_service_dendu' => 'required|string',
            'txt_etat_cession' => 'required|straing',
            'txt_cession_definitive' => 'required|string',
            'dt_date_creation' => 'reqired|date',


            //table Departement
            'slt_region' => 'required|string',

            //table Departement
            'slt_departement' => 'required|string',

            //table Arrondissement
            'slt_arrondissement' => 'required|string',

            //table Commune
            'slt_commune' => 'required|string',

            //table Titulaire_terrain (table association entre Titulaire <=> Terrain)
             //'titulaire_id'
            //'terrain_id'

            //table Terrain
            'txt_lotissement' => 'required|string',
            'txt_num_lotissement' => 'required|string',
            'txt_num_section' => 'required|string',
            'txt_num_parcelle' => 'required|straing',
            'txt_num_titre' => 'required|string',
            'nbr_surface' => 'required|string',
            'slt_document_admin' => 'required|string',
            'txt_num_deliberation' => 'required|string',
            'dt_date_deliberation' => 'required|date',
            'txt_nicad' => 'required|string',

            // table Reference_Cadastral
            'rd_immatricalation_terrain' => 'required|boolean',
            'slt_dependant_domaine' => 'required|string',
            'slt_bornage' => 'required|string',
            'ussu_bornage' => 'required|string',
            'txt_titre_mere' => 'required|string',
            'slt_lf' => 'required|string',
            'txt_num_requisition' => 'required|string',
            'txt_surface_bornage' => 'required|string',
            'dt_date_bornage' => 'required|date',
            'txt_nom_geometre' => 'required|string',

            // table Titulaire
            'slt_titulaire' => 'required|string',
            'txt_nationalite' => 'required|string',
            'slt_civilite' => 'required|string',
            'txt_prenom' => 'required|string',
            'txt_nom' => 'required|string',
            'slt_piece' => 'required|string',
            'txt_numPiece' => 'required|string',
            'dt_date_delivrance' => 'required|string',
            'dt_date_naissance' => 'required|string',
            'txt_lieu_naissance' => 'required|string',
            'txt_adresse' => 'required|string',
            'tel_telephone' => 'required|string',
            'txt_ninea' => 'required|string',
            'eml_email' => 'nullable|string',
            'txt_representant' => 'nullable|string',
            'txt_telRepresentant' => 'nullable|string',

            //table Reference_usage
            'slt_reference_usage' => 'nullable|string',
            'txt_occupan_habitaion_1' => 'nullable|string',
            'txt_activite_principal_hbt_1' => 'nullable|string',
            'txt_ninea_occupan_hbt_1' => 'nullable|string',
            'tel_tel_occupant_hbt_1' => 'nullable|string',
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
            'chk_bati_principal' => 'default(false)|boolean',
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
            'chk_cours_amenager_totale' => 'nullable|string',
            'nbr_surface_ca_total' => 'nullable|string',
            'slt_categorie_ca_total' => 'nullable|string',
            'nbr_prix_metre_carre_ca_total' => 'nullable|string',       //default(false),
            'nbr_coefficient_ca_total' => 'nullable|string',
            'nbr_valeur_ca_total' => 'nullable|integer',
            'nbr_valeur_total_cours' => 'nullable|integer',

            //table Evaluation_cloture
            'chk_perimetre_cloture' => 'nullable|string',
            'nbr_longueur_avant_clo' => 'nullable|string',
            'slt_categorie_clo' => 'nullable|string',
            'nbr_prix_metre_carre_clo' => 'default(false)|boolean',
            'nbr_coefficient_clo' => 'nullable|string',
            'nbr_valeur_clo' => 'nullable|integer',
            'nbr_valeur_total_clotur' => 'nullable|integer',

            // table Evalution_amenagement
            'txt_designation_am' => 'nullable|string',
            'nbr_valeur_unitaire_am' => 'nullable|string',
            'nbr_quantile_am' => 'nullable|string',
            'slt_coeficien_am' => 'default(false)|boolean',
            'nbr_valeur_am' => 'nullable|string',
            'nbr_valeur_totale_ap' => 'nullable|integer',


        ]);
        try{

        // PAGES SECRETAIRE 

            $dossier = new Dossier;
            $dossier->txt_num_dossier = $request->txt_num_dossier;
            $dossier->txt_num_dordre = $request->txt_num_dordre;
            $dossier->slt_service_dendu = $request->slt_service_dendu;
            $dossier->txt_etat_cession = $request->txt_etat_cession;
            $dossier->txt_cession_definitive = $request->txt_cession_definitive;
            $dossier->dt_date_creation = $request->dt_date_creation;
            $dossier->save();

            //table Region
            $region = new Region;
            $region->slt_region =  $request->slt_region;
            $region->save();

            //table Departement
            $departement = new Departement;
            $departement->slt_departement = $request->slt_departement;
            $departement->save();

            //table Arrondissement
            $arrondissement = new Arrondissement;
            $arrondissement->slt_arrondissement = $request->slt_arrondissement;
            $arrondissement->save();

            //table Coommune
            $commune = new Commune;
            $commune->slt_commune = $request->slt_commune;
            $commune->save();

            //table Terrain 
            $terrain = new Terrain;
            $terrain->txt_lotissement = $request->txt_lotissement;
            $terrain->txt_num_lotissement = $request->txt_num_lotissement;
            $terrain->txt_num_section = $request->txt_num_section;
            $terrain->txt_num_parcelle = $request->txt_num_parcelle;
            $terrain->txt_num_titre = $request->txt_num_titre;
            $terrain->nbr_surface = $request->nbr_surface;
            $terrain->slt_document_admin = $request->slt_document_admin;
            $terrain->txt_num_deliberation = $request->txt_num_deliberation;
            $terrain->dt_date_deliberation = $request->dt_date_deliberation;
            $terrain->txt_nicad = $request->txt_nicad;
            $terrain->save();

            // table Reference_Cadastral
            $reference_cadastrale = new ReferenceCadastrale;
            $reference_cadastrale->rd_immatricalation_terrain = $request->rd_immatricalation_terrain;
            $reference_cadastrale->slt_dependant_domaine = $request->slt_dependant_domaine;
            $reference_cadastrale->slt_bornage = $request->slt_bornage;
            $reference_cadastrale->ussu_bornage = $request->ussu_bornage;
            $reference_cadastrale->txt_titre_mere = $request->txt_titre_mere;
            $reference_cadastrale->slt_lf = $request->slt_lf;
            $reference_cadastrale->txt_num_requisition = $request->txt_num_requisition;
            $reference_cadastrale->txt_surface_bornage = $request->txt_surface_bornage;
            $reference_cadastrale->dt_date_bornage = $request->dt_date_bornage;
            $reference_cadastrale->txt_nom_geometre = $request->txt_nom_geometre;
            $reference_cadastrale->save();

            // table Titulaire
            $titulaire = new Titulaire;
            $titulaire->slt_titulaire = $request->slt_titulaire;
            $titulaire->txt_nationalite = $request->txt_nationalite;
            $titulaire->slt_civilite = $request->slt_civilite;
            $titulaire->txt_prenom = $request->txt_prenom;
            $titulaire->txt_nom = $request->txt_nom;
            $titulaire->slt_piece = $request->slt_piece;
            $titulaire->txt_numPiece = $request->txt_numPiece;
            $titulaire->dt_date_delivrance = $request->dt_date_delivrance;
            $titulaire->dt_date_naissance = $request->dt_date_naissance;
            $titulaire->txt_lieu_naissance = $request->txt_lieu_naissance;
            $titulaire->txt_adresse = $request->txt_adresse;
            $titulaire->tel_telephone = $request->tel_telephone;
            $titulaire->txt_ninea = $request->txt_ninea;
            $titulaire->eml_email = $request->eml_email;
            $titulaire->txt_representant = $request->txt_representant;
            $titulaire->txt_telRepresentant = $request->txt_telRepresentant;
            $titulaire->save();


        // PAGES GEOMETRE  


            //table Reference_usage
            $reference_usage = new ReferenceUsage;
            $reference_usage->slt_reference_usage = $request->slt_reference_usage;
            $reference_usage->txt_occupan_habitaion_1 = $request->txt_occupan_habitaion_1;
            $reference_usage->txt_activite_principal_hbt_1 = $request->txt_activite_principal_hbt_1;
            $reference_usage->txt_ninea_occupan_hbt_1 = $request->txt_ninea_occupan_hbt_1;
            $reference_usage->tel_tel_occupant_hbt_1 = $request->tel_tel_occupant_hbt_1;
            $reference_usage->nbr_montant_loyer_hbt_1 = $request->nbr_montant_loyer_hbt_1;
            $reference_usage->txt_activite_commercial = $request->txt_activite_commercial;
            $reference_usage->txt_occopan_commercial = $request->txt_occopan_commercial;
            $reference_usage->txt_activite_industriel = $request->txt_activite_industriel;
            $reference_usage->txt_occopan_industriel = $request->txt_occopan_industriel;
            $reference_usage->txt_activite_agricole = $request->txt_activite_agricole;
            $reference_usage->txt_occopan_agricole = $request->txt_occopan_agricole;
            $reference_usage->txt_activite_professionnelle = $request->txt_activite_professionnelle;
            $reference_usage->txt_occopan_professionnelle = $request->txt_occopan_professionnelle;
            $reference_usage->txt_activite_culte = $request->txt_activite_culte;
            $reference_usage->txt_occopan_culte = $request->txt_occopan_culte;
            $reference_usage->txt_Activite_administratif = $request->txt_Activite_administratif;
            $reference_usage->txt_occupan_administratif = $request->txt_occupan_administratif;
            $reference_usage->save();

            // table evaluation_terrains
            $evaluation_terrain = new EvaluationTerrain;
            $evaluation_terrain->txt_date_devaluation = $request->txt_date_devaluation;
            $evaluation_terrain->txt_superficie_totale = $request->txt_superficie_totale;
            $evaluation_terrain->txt_superficie_bati_sol = $request->txt_superficie_bati_sol;
            $evaluation_terrain->slt_secteur = $request->slt_secteur;
            $evaluation_terrain->nbr_prix_metre_carré = $request->nbr_prix_metre_carré;
            $evaluation_terrain->nbr_valeur_terrain = $request->nbr_valeur_terrain;
            $evaluation_terrain->save();

            
            // table Evaluation_batis
            $evaluation_bati = new EvaluationBati;
            $evaluation_bati->slt_type_residence = $request->slt_type_residence;
            $evaluation_bati->rd_type_maissons = $request->rd_type_maissons;
            $evaluation_bati->chk_bati_principal = $request->chk_bati_principal;
            $evaluation_bati->slt_cat = $request->slt_cat;
            $evaluation_bati->nbr_prix_metre_carre = $request->nbr_prix_metre_carre;
            $evaluation_bati->nbr_surface_bati_sol = $request->nbr_surface_bati_sol;
            $evaluation_bati->nbr_niveau = $request->nbr_niveau;
            $evaluation_bati->nbr_surface_brute = $request->nbr_surface_brute;
            $evaluation_bati->nbr_surface_utile = $request->nbr_surface_utile;
            $evaluation_bati->slt_coeff = $request->slt_coeff;
            $evaluation_bati->nbr_surface_corriger = $request->nbr_surface_corriger;
            $evaluation_bati->nbr_valeur = $request->nbr_valeur;
            $evaluation_bati->txt_valeur_terrain_bati = $request->txt_valeur_terrain_bati;
            $evaluation_bati->save();

            
            // table evaluation_cours_amenagees
            $evaluation_cours_amenagee = new EvaluationCoursAmenagee;
            $evaluation_cours_amenagee->chk_cours_amenager_totale = $request->chk_cours_amenager_totale;
            $evaluation_cours_amenagee->nbr_surface_ca_total = $request->nbr_surface_ca_total;
            $evaluation_cours_amenagee->slt_categorie_ca_total = $request->slt_categorie_ca_total;
            $evaluation_cours_amenagee->nbr_prix_metre_carre_ca_total = $request->nbr_prix_metre_carre_ca_total;    //default(false),
            $evaluation_cours_amenagee->nbr_coefficient_ca_total = $request->nbr_coefficient_ca_total;
            $evaluation_cours_amenagee->nbr_valeur_ca_total = $request->nbr_valeur_ca_total;
            $evaluation_cours_amenagee->nbr_valeur_total_cours = $request->nbr_valeur_total_cours;
            $evaluation_cours_amenagee->save();

            //table Evaluation_cloture
            $evaluation_cloture = new EvaluationCloture;
            $evaluation_cloture->chk_perimetre_cloture = $request->chk_perimetre_cloture;
            $evaluation_cloture->nbr_longueur_avant_clo = $request->nbr_longueur_avant_clo;
            $evaluation_cloture->slt_categorie_clo = $request->slt_categorie_clo;
            $evaluation_cloture->nbr_prix_metre_carre_clo = $request->nbr_prix_metre_carre_clo;
            $evaluation_cloture->nbr_coefficient_clo = $request->nbr_coefficient_clo;
            $evaluation_cloture->nbr_valeur_clo = $request->nbr_valeur_clo;
            $evaluation_cloture->nbr_valeur_total_clotur = $request->nbr_valeur_total_clotur;
            $evaluation_cloture->save();



            // table Evalution_amenagement
            $evaluation_amenagement = new EvaluationAmenagement;
            $evaluation_amenagement->txt_designation_am = $request->txt_designation_am;
            $evaluation_amenagement->nbr_valeur_unitaire_am = $request->nbr_valeur_unitaire_am;
            $evaluation_amenagement->nbr_quantile_am = $request->nbr_quantile_am;
            $evaluation_amenagement->slt_coeficien_am = $request->slt_coeficien_am;
            $evaluation_amenagement->nbr_valeur_am = $request->nbr_valeur_am;
            $evaluation_amenagement->nbr_valeur_totale_ap = $request->nbr_valeur_totale_ap;
            $evaluation_amenagement->save();

            return redirect()->route('secretariat.create')->with('success ', 'Enregistrement réussit');
        }catch(\Exception $e) {
            return redirect()->route('secretariat.create')->with('fail',$e->getMessage());
        }


    }


    /* voici l'ensemble des tables

        Schema::create('dossiers', function (Blueprint $table) {
            $table->id();
            $table->string('txt_num_dossier');
            $table->string('txt_num_dordre');
            $table->string('slt_service_rendu');
            $table->string('txt_etat_cession');
            $table->string('txt_cession_definitive');
            $table->date('dt_date_creation');
            $table->timestamps();
        });


        Schema::create('regions', function (Blueprint $table) {
            $table->id();
            $table->string('slt_region');
            $table->timestamps();
        });



        Schema::create('departements', function (Blueprint $table) {
            $table->id();
            $table->string('slt_departement');
            $table->foreignId('region_id')->constrained('regions')->onDelete('cascade');
            $table->timestamps();
        });




        Schema::create('arrondissements', function (Blueprint $table) {
            $table->id();
            $table->string('slt_arrondissement');
            $table->foreignId('departement_id')->constrained('departements')->onDelete('cascade');
            $table->timestamps();
        });




        Schema::create('communes', function (Blueprint $table) {
            $table->id();
            $table->string('slt_commune');
            $table->foreignId('arrondissement_id')->constrained('arrondissements')->onDelete('cascade');
            $table->timestamps();
        });



        Schema::create('titulaire_terrain', function (Blueprint $table) {
            $table->id();
            $table->foreignId('titulaire_id')->constrained('titulaires')->onDelete('cascade');
            $table->foreignId('terrain_id')->constrained('terrains')->onDelete('cascade');
            $table->timestamps();
        });




        Schema::create('terrains', function (Blueprint $table) {
            $table->id();
            $table->string('txt_lotissement');
            $table->string('txt_num_lotissement');
            $table->string('txt_num_section');
            $table->string('txt_num_parcelle');
            $table->string('txt_num_titre');
            $table->string('txt_titre_mere');
            $table->integer('nbr_surface');
            $table->string('slt_document_admin');
            $table->string('txt_num_deliberation');
            $table->date('dt_date_deliberation');
            $table->string('txt_nicad');

            // Foreign keys for location hierarchy
            $table->foreignId('region_id')->constrained('regions')->onDelete('cascade');
            $table->foreignId('departement_id')->constrained('departements')->onDelete('cascade');
            $table->foreignId('arrondissement_id')->constrained('arrondissements')->onDelete('cascade');
            $table->foreignId('commune_id')->constrained('communes')->onDelete('cascade');

            // Foreign key for dossier
            $table->foreignId('dossier_id')->constrained('dossiers')->onDelete('cascade');

            $table->timestamps();
        });




        Schema::create('references_cadastrales', function (Blueprint $table) {
            $table->id();
            $table->boolean('rd_immatricalation_terrain');
            $table->string('slt_dependant_domaine');
            $table->string('slt_bornage');
            $table->string('slt_dependant_domaine');
            $table->string('ussu_bornage');
            $table->string('txt_titre_mere');
            $table->string('slt_lf');
            $table->string('txt_num_requisition');
            $table->string('txt_surface_bornage');
            $table->string('dt_date_bornage');
            $table->string('txt_nom_geometre');
            $table->timestamps();
        });



        Schema::create('titulaires', function (Blueprint $table) {
            $table->id();
            $table->string('slt_titulaire');
            $table->string('txt_nationalite');
            $table->string('slt_civilite');
            $table->string('txt_prenom');
            $table->string('txt_nom');
            $table->string('slt_piece');
            $table->string('txt_numPiece');
            $table->date('dt_date_delivrance');
            $table->date('dt_date_naissance');
            $table->string('txt_lieu_naissance');
            $table->string('txt_adresse');
            $table->string('tel_telephone');
            $table->string('txt_ninea');
            $table->string('txt_representant')->nullable();
            $table->string('eml_email')->nullable();
            $table->string('txt_telRepresentant')->nullable();
            $table->timestamps();
        });

        //modofier migra

        Schema::create('recherches', function (Blueprint $table) {
            $table->id();
            $table->string('sh_search');
            $table->timestamps();
        });



        Schema::create('references_usages', function (Blueprint $table) {
            $table->id();
            $table->string('slt_reference_usage');
            $table->string('txt_occupan_habitaion_1')->nullable();
            $table->string('txt_activite_principal_hbt_1')->nullable();
            $table->string('txt_ninea_occupan_hbt_1')->nullable();
            $table->string('tel_tel_occupant_hbt_1')->nullable();
            $table->integer('nbr_montant_loyer_hbt_1')->nullable();
            $table->string('txt_activite_commercial')->nullable();
            $table->string('txt_occopan_commercial')->nullable();
            $table->string('txt_activite_industriel')->nullable();
            $table->string('txt_occopan_industriel')->nullable();
            $table->string('txt_activite_agricole')->nullable();
            $table->string('txt_occopan_agricole')->nullable();
            $table->string('txt_activite_professionnelle')->nullable();
            $table->string('txt_occopan_professionnelle')->nullable();
            $table->string('txt_activite_culte')->nullable();
            $table->string('txt_occopan_culte')->nullable();
            $table->string('txt_Activite_administratif')->nullable();
            $table->string('txt_occupan_administratif')->nullable();
            $table->timestamps();
        });



        Schema::create('evaluations_terrains', function (Blueprint $table) {
            $table->id();
            $table->date('txt_date_devaluation');
            $table->integer('txt_superficie_totale');
            $table->integer('txt_superficie_bati_sol');
            $table->string('slt_secteur');
            $table->integer('nbr_prix_metre_carré');
            $table->integer('nbr_valeur_terrain');

            $table->foreignId('terrain_id')->constrained('terrains')->onDelete('cascade');
            $table->timestamps();
        });



        Schema::create('evaluations_batis', function (Blueprint $table) {
            $table->id();
            $table->string('slt_type_residence')->nullable();
            $table->string('rd_type_maissons')->nullable();
            $table->boolean('chk_bati_principal')->default(false);
            $table->string('slt_cat')->nullable();
            $table->integer('nbr_prix_metre_carre')->nullable();
            $table->integer('nbr_surface_bati_sol')->nullable();
            $table->integer('nbr_niveau')->nullable();
            $table->integer('nbr_surface_brute')->nullable();
            $table->integer('nbr_surface_utile')->nullable();
            $table->string('slt_coeff')->nullable();
            $table->integer('nbr_surface_corriger')->nullable();
            $table->integer('nbr_valeur')->nullable();
            $table->integer('txt_valeur_terrain_bati')->nullable();

            $table->foreignId('terrain_id')->constrained('terrains')->onDelete('cascade');
            $table->timestamps();
        });


        Schema::create('evaluations_cours_amenagees', function (Blueprint $table) {
            $table->id();
            $table->string('chk_cours_amenager_totale')->nullable();
            $table->string('nbr_surface_ca_total')->nullable();
            $table->string('slt_categorie_ca_total')->nullable();
            $table->boolean('nbr_prix_metre_carre_ca_total')->default(false);
            $table->string('nbr_coefficient_ca_total')->nullable();
            $table->integer('nbr_valeur_ca_total')->nullable();
            $table->integer('nbr_valeur_total_cours')->nullable();

            $table->foreignId('terrain_id')->constrained('terrains')->onDelete('cascade');
            $table->timestamps();
        });


        Schema::create('evaluations_clotures', function (Blueprint $table) {
            $table->id();
            $table->string('chk_perimetre_cloture')->nullable();
            $table->string('nbr_longueur_avant_clo')->nullable();
            $table->string('slt_categorie_clo')->nullable();
            $table->boolean('nbr_prix_metre_carre_clo')->default(false);
            $table->string('nbr_coefficient_clo')->nullable();
            $table->integer('nbr_valeur_clo')->nullable();
            $table->integer('nbr_valeur_total_clotur')->nullable();

            $table->foreignId('terrain_id')->constrained('terrains')->onDelete('cascade');
            $table->timestamps();
        });


        Schema::create('evaluations_amenagement', function (Blueprint $table) {
            $table->id();
            $table->string('txt_designation_am')->nullable();
            $table->string('nbr_valeur_unitaire_am')->nullable();
            $table->string('nbr_quantile_am')->nullable();
            $table->boolean('slt_coeficien_am')->default(false);
            $table->string('nbr_valeur_am')->nullable();
            $table->integer('nbr_valeur_totale_ap')->nullable();

            $table->foreignId('terrain_id')->constrained('terrains')->onDelete('cascade');
            $table->timestamps();
        });


    */
}
