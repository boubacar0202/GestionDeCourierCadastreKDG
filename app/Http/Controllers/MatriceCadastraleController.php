<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class MatriceCadastraleController extends Controller
{
    //
       /*
    public function index(){
        return Inertia::render("matriceCadastrale/index");
    }
    */

    public function create(){
        return Inertia::render("matriceCadastrale/create");
    }
}
