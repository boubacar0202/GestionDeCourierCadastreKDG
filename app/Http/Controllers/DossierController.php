<?php

namespace App\Http\Controllers;

use App\Models\Dossier;

class DossierController extends Controller
{
    //
    public function getNextDossier()
    {
        $lastDossier = Dossier::whereYear('created_at', date('Y'))
            ->orderBy('id', 'desc')
            ->first();
    
        $number = $lastDossier ? (int)substr($lastDossier->txt_num_dossier, 0, 6) + 1 : 1;
    
        $nextNumDossier = sprintf('%06d/%s', $number, date('Y'));
    
        return response()->json([
            'num_dossier' => $nextNumDossier
        ]);
    }
}
