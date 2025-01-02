<?php

namespace App\Http\Controllers;

use App\Models\Donnee;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DonneeController extends Controller
{
    //
    public function index(){
        $donnees = Donnee::all();
        return Inertia::render("donnee/index", ["donnees"=> $donnees]);

    }
    public function create(){
        return Inertia::render("donnee/create");
    }
}
