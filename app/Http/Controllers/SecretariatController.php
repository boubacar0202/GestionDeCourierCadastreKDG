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
        //   $validatedData = $request->all();

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
            'slt_region' => 'required|string',
            'selectedDepartement' => 'required|string',
            'selectedArrondissement' => 'required|string',
            'selectedCommune' => 'required|string',

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
            'slt_titulaire' => 'nullable|string|min:1|max:255',
            'txt_nationalite' => 'nullable|string|min:1|max:255',
            'slt_civilite' => 'nullable|string|min:1|max:255',
            'txt_prenom' => 'nullable|string|min:1|max:255',
            'txt_nom' => 'nullable|string|min:1|max:255',
            'slt_piece' => 'nullable|string|min:1|max:255',
            'txt_numPiece' => 'nullable|string|min:1|max:255',
            'dt_date_delivrance' => 'nullable|date',
            'dt_date_naissance' => 'nullable|date',
            'txt_lieu_naissance' => 'nullable|string|min:1|max:255',
            'txt_adresse' => 'nullable|string|min:1|max:255',
            'tel_telephone' => 'nullable|numeric',
            'txt_ninea' => 'nullable|string',
            'eml_email' => 'nullable|email',
            'txt_representant' => 'nullable|string',
            'tel_Representant' => 'nullable|numeric|digits_between:10,15',

            // table Localite

        ]);

        DB::beginTransaction();
        try {

            // Vérification de la duplication du dossier
            $dossierExist = Dossier::where('numDossier', $validatedData['numDossier'])->first();
            if ($dossierExist) {
                return response()->json(['error' => 'Le dossier avec ce numéro existe déjà.']);
            }

            // Vérification de la duplication du terrain
            $terrainExist = Terrain::where('txt_nicad', $validatedData['txt_nicad'])->first();
            if ($terrainExist) {
                return response()->json(['error' => 'Le terrain avec ce NICAD existe déjà.']);
            }

            
            $region = Region::where('id', $validatedData['slt_region'])->firstOrFail();
            $departement = Departement::where('id', $validatedData['slt_departement'])->firstOrFail();
            $arrondissement = Arrondissement::where('id', $validatedData['slt_arrondissement'])->firstOrFail();
            $commune = Commune::where('id', $validatedData['slt_commune'])->firstOrFail();

            // dd($region);
            
            // $dossier = Dossier::create([
            //     'numDossier' => $validatedData['numDossier'],
            //     'numDordre' => $validatedData['numDordre'],
            //     'serviceRendu' => $validatedData['serviceRendu'],
            //     'etatCession' => $validatedData['etatCession'],
            //     'cessionDefinitive' => $validatedData['cessionDefinitive'],
            //     'dateCreation' => $validatedData['dateCreation'],
            // ]);

            // //table Terrain
            // $terrain = Terrain::create([
            //     'txt_lotissement' => $validatedData['txt_lotissement'] ?? null,
            //     'txt_num_lotissement' => $validatedData['txt_num_lotissement'] ?? null,
            //     'txt_num_section' => $validatedData['txt_num_section'] ?? null,
            //     'txt_num_parcelle' => $validatedData['txt_num_parcelle'] ?? null,
            //     'txt_num_titre' => $validatedData['txt_num_titre'] ?? null,
            //     'nbr_surface' => $validatedData['nbr_surface'] ?? null,
            //     'slt_document_admin' => $validatedData['slt_document_admin'] ?? null,
            //     'txt_num_deliberation' => $validatedData['txt_num_deliberation'] ?? null,
            //     'dt_date_deliberation' => $validatedData['dt_date_deliberation'] ?? null,
            //     'txt_nicad' => $validatedData['txt_nicad'] ?? null,
            //     'region_id' => $region->id,
            //     'departement_id' => $departement->id,
            //     'arrondissement_id' => $arrondissement->id,
            //     'commune_id' => $commune->id,
            // ]);
            

            // // table Reference_Cadastral
            // $referenceCadastrale = ReferenceCadastrale::create([
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
            // $titulaire = Titulaire::create([
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
            //     'tel_representant' => $validatedData['tel_representant'],
            // ]);

            Model::create($validatedData);
            
          
            return redirect()->json(['message'=> 'Dossier enregistré avec succès.']);
        } catch (\Exception $e) {
            DB::rollback();
            return back()->json(['error' =>  $e->getMessage()]);
        }
    }
}
