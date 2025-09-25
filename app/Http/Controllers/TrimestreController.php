<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Arrivee;
use App\Models\Depart; 
use App\Models\Trimestre;
use Carbon\Carbon; 
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TrimestreController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $trimestres = Trimestre::all();
        $morcellementsCount = Arrivee::where(DB::raw('LOWER(txt_designation)'), 'like', '%Morcellements%')->count();
   
        return Inertia::render('trimestre/create', [
            'trimestres' => $trimestres,
            'morcellementsCount' => $morcellementsCount
        ]);
    }
     
    public function create()
    {
        // 
 
        $trimestres = Trimestre::orderBy('id')->get(); 

        $now = Carbon::now();
            $currentQuarter = $now->quarter;
            $year = $now->year;

        $startOfQuarter = Carbon::createFromDate($year, (($currentQuarter - 1) * 3) + 1, 1)->startOfDay();
        $endOfQuarter = (clone $startOfQuarter)->addMonths(3)->subDay()->endOfDay();
 
        $designations = [
            "Morcellements",
            "Réquisition d\'immatriculation",
            "Demande de terrain / Echange",
            "Prospection de terrain",
            "Autorisation de construction",
            "Autorisation de lotir",
            "Demande d\'états des lieux",
            "Demande de délimitation/reconstruction",
            "Réquisition DSCOS, Tribunal, Litiges",
            "Demande d’extraits de plan",
            "Demande de situation foncière",
            "Demande de Cession définitive",
            "Demande de Cession définitive a Titre Gratuit",
            "Demande de Régularisation",
            "Demande d\'attestation du Cadastre",
            "Projets de Lotissements reçus",
            "Réceptions de lotissements",
            "Lotissements réalisés sans respect des procédures",
            "Demande de CIC",
            "Demande de Titre foncier",
            "Autirisationde morceler",
            "Demande d’évaluation",
            "Nombre de fiches de mise à jour reçues",
            "Nombre de dossiers techniques en attente de fiches de mise à jour"
        ];
 
        // Pour chaque désignation, compter les enregistrements dans le
        $countsByTrimestre = [];

        foreach ($designations as $designation) {
            $countsByTrimestre[$designation] = Arrivee::where('txt_designation', 'like', "%{$designation}%")
                ->whereBetween('dt_datearrivee', [$startOfQuarter, $endOfQuarter])
                ->count();
        }

        // Pour chaque désignation, compter les traiter 
        $arriveeRefCol = 'txt_reference'; 
 
        $countsTraiterByTrimestre = []; 
        foreach ($designations as $designation) {
            // Nombre reçus (Arrivées qui contiennent la désignation)
            $countsByTrimestre[$designation] = Arrivee::where('txt_designation', 'like', "%{$designation}%")
                ->whereBetween('dt_datearrivee', [$startOfQuarter, $endOfQuarter])
                ->count();

            // Nombre traités : on compte les ARRIVÉES pour lesquelles il existe au moins un DEPART 
            $countsTraiterByTrimestre[$designation] = Arrivee::where('txt_designation', 'like', "%{$designation}%")
                ->whereBetween('dt_datearrivee', [$startOfQuarter, $endOfQuarter])
                ->whereExists(function ($query) use ($startOfQuarter, $endOfQuarter, $arriveeRefCol) {
                    $query->select(DB::raw(1))
                        ->from('departs')
                        ->whereRaw("departs.txt_referencecourierarriveecd LIKE CONCAT('%', arrivees.{$arriveeRefCol}, '%')")
                        ->where('departs.txt_categoriecd', 'Reponse à un Courrier arrivé')
                        ->whereBetween('departs.dt_datecouriercd', [$startOfQuarter, $endOfQuarter]);
                })
                ->count();
        }

        // Pour chaque désignation, calculer le stockfin
        $stockFin = []; 
        foreach ($designations as $designation) {
            // Total des reçus avant le trimestre courant
            $totalRecusAvant = Arrivee::where('txt_designation', 'like', "%{$designation}%")
                ->where('dt_datearrivee', '<', $startOfQuarter)
                ->count(); 

            // Stock fin = cumul avant le trimestre courant
            $stockFin[$designation] = $totalRecusAvant;
        }

        // Calculer les instances restantes
        $instancesRestantes = []; 
        foreach ($designations as $designation) {
            // Total des reçus avant le trimestre courant
            $totalRecusInstance = Arrivee::where('txt_designation', 'like', "%{$designation}%")
                ->whereBetween('dt_datearrivee', [$startOfQuarter, $endOfQuarter])
                ->count();

            // Total des reçus avant le trimestre courant
            $totalRecusAvantInstance = Arrivee::where('txt_designation', 'like', "%{$designation}%")
                ->where('dt_datearrivee', '<', $startOfQuarter)
                ->count(); 
            
            $recuInstance = $totalRecusInstance + $totalRecusAvantInstance;

            // Total des traités avant le trimestre courant
            $totalTraitesAvant = Arrivee::where('txt_designation', 'like', "%{$designation}%")
                ->whereBetween('dt_datearrivee', [$startOfQuarter, $endOfQuarter])
                ->whereExists(function ($query) use ($startOfQuarter, $endOfQuarter, $arriveeRefCol) {
                    $query->select(DB::raw(1))
                        ->from('departs')
                        ->whereRaw("departs.txt_referencecourierarriveecd LIKE CONCAT('%', arrivees.{$arriveeRefCol}, '%')")
                        ->where('departs.txt_categoriecd', 'Reponse à un Courrier arrivé')
                        ->whereBetween('departs.dt_datecouriercd', [$startOfQuarter, $endOfQuarter]);
                })
                ->count();
  
            // Stock fin = cumul avant le trimestre courant
            $instancesRestantes[$designation] = $recuInstance - $totalTraitesAvant;
        }

        return Inertia::render('trimestre/create', [ 
            'trimestres' => $trimestres,
            'countsByTrimestre' => $countsByTrimestre,
            'countsTraiterByTrimestre' => $countsTraiterByTrimestre,
            'stockFin' => $stockFin,
            'instancesRestantes' => $instancesRestantes

        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
