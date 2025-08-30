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
    
    public function countMorcellementsThisQuarter()
    {
        $now = Carbon::now();

        // Déterminer le trimestre en cours
        $currentQuarter = $now->quarter;
        $year = $now->year;
 
        $startOfQuarter = Carbon::createFromDate($year, (($currentQuarter - 1) * 3) + 1, 1)->startOfDay();
        $endOfQuarter = (clone $startOfQuarter)->addMonths(3)->subDay()->endOfDay();
 
        $count = Arrivee::where('txt_designation', 'like', '%Morcellements%')
            ->whereBetween('dt_datearrivee', [$startOfQuarter, $endOfQuarter])
            ->count();

        return response()->json(['total_morcellements' => $count]);
        
    }
 
    public function create()
    {
        // 
 
        $trimestres = Trimestre::orderBy('id')->get();

        return Inertia::render('trimestre/create', [ 
            'trimestres' => $trimestres
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
