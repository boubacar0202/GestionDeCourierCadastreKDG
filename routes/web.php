<?php

use App\Http\Controllers\DonneeController;
use App\Http\Controllers\GeometreController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\MatriceCadastraleController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SecretariatController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::resource('secretariat', SecretariatController::class);
Route::resource('geometre', GeometreController::class);
Route::resource('donnee', DonneeController::class);
Route::resource('matriceCadastrale', MatriceCadastraleController::class);


Route::get('/departements/{regionId}', [LocationController::class, 'getDepartementsByRegion']);
Route::get('/arrondissements/{departementId}', [LocationController::class, 'getArrondissementByDepartement']);
Route::get('/communes/{arrondissementId}', [LocationController::class, 'getCommunesByArrondissement']);


require __DIR__ . '/auth.php';
