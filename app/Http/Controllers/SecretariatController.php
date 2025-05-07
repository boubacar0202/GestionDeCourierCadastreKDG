<?php

namespace App\Http\Controllers;


use App\Models\Region;
use App\Models\Departement;
use App\Models\Arrondissement;
use App\Models\Commune;
use App\Models\Dossier;
use App\Models\ReferenceCadastrale;
use App\Models\Terrain;
use App\Models\Titulaire;
use Illuminate\Http\Request;
use Inertia\Inertia;


class SecretariatController extends Controller
{

    //
    public function index()
    {

        $terrains = Terrain::with(['region', 'departement', 'arrondissement', 'commune', 'dossier'])->get();
        
        return Inertia::render("secretariat/index",  [
            "terrains" => $terrains,
        ]);

    }

    public function create()
    {
        $regions = Region::all();

        return Inertia::render("secretariat/create",  [
            "regions" => $regions,
            "departements" => [],
        ]);
    }

    // private function generateNextSlug()
    // {

    //     // Récupérer le dernier dossier de l'année en cours
    //     $lastDossier = Dossier::whereYear('created_at', date('Y'))
    //                 ->orderBy('id', 'desc')
    //                 ->first();
    //     $number = $lastDossier ?
    //     (int)substr($lastDossier->txt_num_dossier, 0, 6) +1 : 1;

    //     // Calculer le prochain numéro
    //     return sprintf('%06d/%s', $number, date('Y'));
        
    // }

    public function store(Request $request)
    {
        // dd($request->all());
        // $validatedData = $request->all();

        $validatedData = $request->validate([
            'txt_num_dossier' => 'required|string|unique:dossiers,txt_num_dossier',
            'slt_service_rendu' => 'required|string',
            'txt_etat_cession' => 'required|string',
            'txt_cession_definitive' => 'nullable|string',
            'dt_date_creation' => 'required|date',

            // table Reference_Cadastral
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
            
            // table Terrain
            // 'txt_num_dossier' => 'required|exists:dossiers,id',
            'txt_lotissement' => 'nullable|string',
            'txt_HorsLotissement' => 'nullable|string',
            'txt_num_lotissement' => 'nullable|string',
            'txt_num_section' => 'nullable|string',
            'txt_num_parcelle' => 'nullable|string',
            'txt_num_titre' => 'nullable|string',
            'nbr_surface' => 'nullable|numeric|min:0|max:99999999.99',
            'txt_appartement' => 'nullable|string',
            'slt_document_admin' => 'nullable|string',
            'txt_num_deliberation' => 'nullable|string',
            'dt_date_deliberation' => 'nullable|date',
            'txt_nicad' => 'nullable|string|unique:terrains,txt_nicad',
            'slt_region' => 'required|exists:regions,id',
            'slt_departement' => 'required|exists:departements,id',
            'slt_arrondissement' => 'required|exists:arrondissements,id',
            'slt_commune' => 'required|exists:communes,id',
 

        ], [
         
            // 'txt_num_dossier.required' => 'Dossier requis.',
            // 'txt_num_dossier.unique' => 'Dossier existe déjà.',

            'txt_num_dordre.required' => "Numéro ordre requis.",
            'txt_num_dordre.integer' => "Numéro ordre en chiffre.",

            'slt_service_rendu.required' => "Le service rendu requis.",
            'txt_etat_cession.required' => "Etat Cession requis.",
            // 'txt_cession_definitive.required' => "La cession definitive est requis.",
            'dt_date_creation.required' => "Date création requis.",
            'dt_date_creation.date' => "Corrige la date.",

            'txt_nicad.nullable' => 'NICAD existant.',

            'slt_region.required' => 'Region requis',
            'slt_departement.required' => 'Departement requis',
            'slt_arrondissement.required' => 'Arrondissement requis',
            'slt_commune.required'=> 'Commune requis',


        ]);     

            $region = Region::find($request->slt_region);
            $departement = Departement::find($request->slt_departement);
            $arrondissement = Arrondissement::find($request->slt_arrondissement);
            $commune = Commune::find($request->slt_commune);
        
            if (!$region || !$departement || !$arrondissement || !$commune) {
                return back()->with('danger', 'Une des relations est introuvable.');
            }

            // Récupérer le dernier numéro d'ordre et incrémenter
            $lastNumOrder = Dossier::max('txt_num_dordre'); // Trouve la valeur max dans la colonne
            $newOrder = $lastNumOrder ? $lastNumOrder + 1 : 1; // Si null, commence à 1


            $dossier = Dossier::create([
                'txt_num_dossier' =>  $validatedData['txt_num_dossier'], 
                'txt_num_dordre' => $newOrder,  // numero d/'incementer 
                'slt_service_rendu' => $validatedData['slt_service_rendu'],
                'txt_etat_cession' => $validatedData['txt_etat_cession'],
                'txt_cession_definitive' => $validatedData['txt_cession_definitive'],
                'dt_date_creation' => $validatedData['dt_date_creation'],
            ]);

            $referenceCadastrale = ReferenceCadastrale::create([   
                'rd_immatriculation_terrain' => $validatedData['rd_immatriculation_terrain'],
                'slt_dependant_domaine' => $validatedData['slt_dependant_domaine'] ?? null,
                'ussu_bornage' => $validatedData['ussu_bornage'] ?? null,
                'txt_titre_mere' => $validatedData['txt_titre_mere'] ?? null,
                'slt_lf' => $validatedData['slt_lf'] ?? null,
                'txt_num_requisition' => $validatedData['txt_num_requisition'] ?? null,
                'txt_surface_bornage' => $validatedData['txt_surface_bornage'] ?? null,
                'dt_date_bornage' =>  $validatedData['dt_date_bornage'] ?? null,
                'txt_nom_geometre' => $validatedData['txt_nom_geometre'] ?? null,
            ]);
    
            $titulaire = Titulaire::create([
                'slt_titulaire' => $validatedData['slt_titulaire'] ?? null,
                'txt_nationalite' => $validatedData['txt_nationalite'] ?? null,
                'slt_civilite' => $validatedData['slt_civilite'] ?? null,
                'txt_prenom' => $validatedData['txt_prenom'] ?? null,
                'txt_nom' => $validatedData['txt_nom'] ?? null,
                'slt_piece' => $validatedData['slt_piece'] ?? null,
                'txt_numPiece' => $validatedData['txt_numPiece'] ?? null,
                'dt_date_delivrance' => $validatedData ['dt_date_delivrance'] ?? null,
                'dt_date_naissance' => $validatedData ['dt_date_naissance'] ?? null,
                'txt_lieu_naissance' => $validatedData['txt_lieu_naissance'] ?? null,
                'txt_adresse' => $validatedData['txt_adresse'] ?? null,
                'tel_telephone' => $validatedData['tel_telephone'] ?? null,
                'txt_ninea' => $validatedData['txt_ninea'] ?? null,
                'eml_email' => $validatedData['eml_email'] ?? null,
                'txt_representant' => $validatedData['txt_representant'] ?? null,
                'tel_telRepresentant' => $validatedData['tel_telRepresentant'] ?? null,
            ]);
        
            // table Terrain
            $terrain = Terrain::create([
                'txt_lotissement' => $validatedData['txt_lotissement'] ?? null,
                'txt_HorsLotissement' => $validatedData['txt_HorsLotissement'] ?? null,
                'txt_num_lotissement' => $validatedData['txt_num_lotissement'] ?? null,
                'txt_num_section' => $validatedData['txt_num_section'] ?? null,
                'txt_num_parcelle' => $validatedData['txt_num_parcelle'] ?? null,
                'txt_num_titre' => $validatedData['txt_num_titre'] ?? null,
                'nbr_surface' => $validatedData['nbr_surface'] ?? null,
                'txt_appartement' => $validatedData['txt_appartement'] ?? null,
                'slt_document_admin' => $validatedData['slt_document_admin'] ?? null,
                'txt_num_deliberation' => $validatedData['txt_num_deliberation'] ?? null,
                'dt_date_deliberation' => $validatedData['dt_date_deliberation'] ?? null,
                'txt_nicad' => $validatedData['txt_nicad'] ?? null,
                'slt_region' => $region->id,
                'slt_departement' => $departement->id,
                'slt_arrondissement' => $arrondissement->id,
                'slt_commune' => $commune->id,
                'txt_num_dossier' => $dossier->id, // Utiliser l'ID réel du dossier // Correction ici
                'referencesCadastrale_id' => $referenceCadastrale->id,
                'titulaire_id' => $titulaire->id,
            ]);

            session([
                'txt_nicad' => $terrain->txt_nicad,
                'txt_num_parcelle' => $terrain->txt_num_parcelle,
            ]);
            

            return redirect()->back()->with('success', 'Donnée enregistrée !');
        
    }

}
