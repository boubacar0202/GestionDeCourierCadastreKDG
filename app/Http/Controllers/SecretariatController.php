<?php

namespace App\Http\Controllers;


use App\Models\Donnee;
use App\Models\Region;
use App\Models\Dossier;
use App\Models\Localite;
use App\Models\ReferenceCadastrale;
use App\Models\Terrain;
use App\Models\Titulaire;
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
            'eml_email' => 'nullable|email',
            'txt_representant' => 'nullable|string',
            'tel_Representant' => 'nullable|numeric|digits_between:10,15',

            // table Localite
            'region' => 'required|string',
            'departement' => 'required|string',
            'arrondissement' => 'required|string',
            'commune' => 'required|string',

        ]);

        DB::beginTransaction();
        try {

            // Vérification de la duplication du dossier
            $dossierExist = Dossier::where('numDossier', $validatedData['numDossier'])->first();
            if ($dossierExist) {
                return redirect()->back()->withErrors(['error' => 'Le dossier avec ce numéro existe déjà.']);
            }

            // Vérification de la duplication du terrain
            $terrainExist = Terrain::where('txt_nicad', $validatedData['txt_nicad'])->first();
            if ($terrainExist) {
                return redirect()->back()->withErrors(['error' => 'Le terrain avec ce NICAD existe déjà.']);
            }

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

            $localites = Localite::create([
                'region' => $validatedData['region'],
                'departement' => $validatedData['departement'],
                'arrondissement' => $validatedData['arrondissement'],
                'commune' => $validatedData['commune'],
            ]);

            // Association entre le titulaire et le terrain
            $terrains->dossier()->associate($dossiers);
            $terrains->localite()->associate($localites);
            
            $terrains->save();
            $dossiers->save();
            $referenceCadastrales->save();
            $titulaires->save();
            $localites->save();

            DB::commit();
            return redirect()->route('secretariat.create')->with('success', 'Dossier enregistré avec succès.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->withErrors(['error' => 'Erreur lors de l\'enregistrement.']);
        }
    }
}
