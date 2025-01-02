<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class GeometreController extends Controller
{
    //
    /*
    public function index(){
        return Inertia::render("geometre/index");
    }
    */

    public function create(){
        return Inertia::render("geometre/create");
    }
}
