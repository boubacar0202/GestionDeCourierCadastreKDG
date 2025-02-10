<?php

namespace App\Http\Controllers;

use App\Models\Donnee;
use App\Models\Region;
use App\Models\Departement;
use App\Models\Arrondissement;
use App\Models\Commune;
use App\Models\Dossier;
use App\Models\ReferenceCadastrale;
use App\Models\Terrain;
use App\Models\Titulaire;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;


class SecretariatController extends Controller
{

    //
    public function index()
    {
        $donnee = Donnee::all();
        // $people = Person::with(['address', 'profession'])->get();
        // return view('people.index', compact('people'));

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
        // dd($request->all());
        // $validatedData = $request->all();

        $validatedData = $request->validate([
            'txt_num_dossier' => 'required|string|unique:dossiers',
            'txt_num_dordre' => 'required|integer',
            'slt_service_rendu' => 'required|string',
            'txt_etat_cession' => 'required|string',
            'txt_cession_definitive' => 'required|string',
            'dt_date_creation' => 'required|date',

            //table Titulaire_terrain (table association entre Titulaire <=> Terrain)
            //'titulaire_id'
            //'terrain_id'

            //table Terrain
            // 'txt_num_dossier' => 'required|exists:dossiers,id',
            'txt_lotissement' => 'nullable|string',
            'txt_num_lotissement' => 'nullable|string',
            'txt_num_section' => 'nullable|string',
            'txt_num_parcelle' => 'nullable|string',
            'txt_num_titre' => 'nullable|string',
            'nbr_surface' => 'nullable|numeric|min:0|regex:/^\d{1,8}(\.\d{1,2})?$/',
            'slt_document_admin' => 'nullable|string',
            'txt_num_deliberation' => 'nullable|string',
            'dt_date_deliberation' => 'nullable|date',
            'txt_nicad' => 'nullable|string',
            'region_id' => 'required|exists:regions,id',
            'departement_id' => 'required|exists:departements,id',
            'arrondissement_id' => 'required|exists:arrondissements,id',
            'commune_id' => 'required|exists:communes,id',
            'slt_region' => 'required|string',
            'slt_departement' => 'required|string',
            'slt_arrondissement' => 'required|string',
            'slt_commune' => 'required|string',


            // table Reference_Cadastral
            // 'activeTab' => 'required|string',
            'rd_immatriculation_terrain' => 'nullable|string',
            'slt_dependant_domaine' => 'nullable|string',
            'ussu_bornage' => 'nullable|string',
            'txt_titre_mere' => 'nullable|string',
            'slt_lf' => 'nullable|string',
            'txt_num_requisition' => 'nullable|string',
            'txt_surface_bornage' => 'nullable|string',
            'dt_date_bornage' => 'nullable|date',
            'txt_nom_geometre' => 'nullable|string',

            // table Titulaire

            'slt_titulaire' => 'nullable|string',
            'txt_nationalite' => 'nullable|string',
            'slt_civilite' => 'nullable|string',
            'txt_prenom' => 'nullable|string',
            'txt_nom' => 'nullable|string',
            'slt_piece' => 'nullable|string',
            'txt_numPiece' => 'nullable|string',
            'dt_date_delivrance' => 'nullable|date',
            'dt_date_naissance' => 'nullable|date',
            'txt_lieu_naissance' => 'nullable|string',
            'txt_adresse' => 'nullable|string',
            'tel_telephone' => 'nullable|string',
            'txt_ninea' => 'nullable|string',
            'eml_email' => 'nullable|string',
            'txt_representant' => 'nullable|string',
            'tel_telRepresentant' => 'nullable|string',

            // table Localite

            

        ], [
            'txt_num_dossier.required' => 'Le numéro du dossier est requis.',
            'txt_num_dossier.unique' => 'Le numéro du dossier existe déjà.',

            'txt_num_dordre.required' => "Le numéro d'ordre est requis.",
            'txt_num_dordre.integer' => "Le numéro d'ordre doit être en chiffre.",

            'slt_service_rendu.required' => "Le service rendu est requis.",
            'txt_etat_cession.required' => "L etat Cession est requis.",
            'txt_cession_definitive.required' => "La cession definitive est requis.",
            'dt_date_creation.required' => "La date de création est requis.",
            'dt_date_creation.dt_date_creation' => "Veuillez choisir une date valide pour la date de création.",
            // Continuer ici l'affichage des erreurs

        ]);
        
            

            Departement::create([
                'slt_departement' => $request->slt_departement,
                'region_id' => $request->region_id, // Assure-toi que cette valeur est bien utilisée
            ]);
            
            $dossier = Dossier::create([...$validatedData, "txt_num_dossier" => $request->txt_num_dossier])->fresh();
            $region = Region::create([...$validatedData, "slt_region" => $request->slt_region])->fresh();
            $departement = Departement::create([...$validatedData, "slt_departement" => $request->slt_departement])->fresh();
            $arrondissement = Arrondissement::create([...$validatedData, "slt_arrondissement" => $request->slt_arrondissement])->fresh();
            $commune = Commune::create([...$validatedData, "slt_commune" => $request->slt_commune])->fresh();
            

            Dossier::create([
                'txt_num_dossier' => $validatedData['txt_num_dossier'],
                'txt_num_dordre' => $validatedData['txt_num_dordre'],
                'slt_service_rendu' => $validatedData['slt_service_rendu'],
                'txt_etat_cession' => $validatedData['txt_etat_cession'],
                'txt_cession_definitive' => $validatedData['txt_cession_definitive'],
                'dt_date_creation' => $validatedData['dt_date_creation'],
            ]);
        

            // table Terrain
            Terrain::create([
                'dossier_id' => $dossier->id, // Correction ici
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
                'region_id' => $region->id,
                'departement_id' => $departement->id,
                'arrondissement_id' => $arrondissement->id,
                'commune_id' => $commune->id,
                
            ]);
            dd($request->all()); 
           

            // // table Reference_Cadastral
            // ReferenceCadastrale::create([
            //     'rd_immatriculation_terrain' => $validatedData['rd_immatriculation_terrain'] ?? null,
            //     'slt_dependant_domaine' => $validatedData['slt_dependant_domaine'] ?? null,
            //     'slt_bornage' => $validatedData['slt_bornage'] ?? null,
            //     'ussu_bornage' => $validatedData['ussu_bornage'] ?? null,
            //     'txt_titre_mere' => $validatedData['txt_titre_mere'] ?? null,
            //     'slt_lf' => $validatedData['slt_lf'] ?? null,
            //     'txt_num_requisition' => $validatedData['txt_num_requisition'] ?? null,
            //     'txt_surface_bornage' => $validatedData['txt_surface_bornage'] ?? null,
            //     'dt_date_bornage' => $validatedData['dt_date_bornage'] ?? null,
            //     'txt_nom_geometre' => $validatedData['txt_nom_geometre'] ?? null,
            // ]);

            // // table Titulaire
            // Titulaire::create([
            //     'slt_titulaire' => $validatedData['slt_titulaire'],
            //     'txt_nationalite' => $validatedData['txt_nationalite'],
            //     'slt_civilite' => $validatedData['slt_civilite'],
            //     'txt_prenom' => $validatedData['txt_prenom'],
            //     'txt_nom' => $validatedData['txt_nom'],
            //     'slt_piece' => $validatedData['slt_piece'],
            //     'txt_numPiece' => $validatedData['txt_numPiece'],
            //     'dt_date_delivrance' => $validatedData['dt_date_delivrance'],
            //     'dt_date_naissance' => $validatedData['dt_date_naissance'],
            //     'txt_lieu_naissance' => $validatedData['txt_lieu_naissance'],
            //     'txt_adresse' => $validatedData['txt_adresse'],
            //     'tel_telephone' => $validatedData['tel_telephone'],
            //     'txt_ninea' => $validatedData['txt_ninea'] ?? null,
            //     'eml_email' => $validatedData['eml_email'] ?? null,
            //     'txt_representant' => $validatedData['txt_representant'] ?? null,
            //     'tel_telRepresentant' => $validatedData['tel_telRepresentant'],
            // ]);

            // dd($validatedData);

        return redirect()->back()->with('success', 'Donnée enregistrée !');

    }
}
