<?php

namespace App\Http\Controllers;

use App\Models\Donnee;
use Illuminate\Foundation\Testing\Concerns\InteractsWithAuthentication;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SecretariatController extends Controller
{
    //
    public function index()
    {
        $donnee=Donnee::all();
        
        return Inertia::render("secretariat/index",  ["donnees"=>$donnee]);
    }

    public function create(){
        return Inertia::render("secretariat/create");
    }
}
