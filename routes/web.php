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

/*
Route::get('/secretariat', function () {
    return Inertia::render('Secretariat');
})->middleware(['auth', 'verified'])->name('secretariat');
*/
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::resource('secretariat', SecretariatController::class);
Route::resource('geometre', GeometreController::class);
Route::resource('donnee', DonneeController::class);
Route::resource('matriceCadastrale', MatriceCadastraleController::class);

Route::get('/departements', [LocationController::class, 'getDepartements']);
Route::get('/arrondissements/{departementId}', [LocationController::class, 'getArrondissements']);
Route::get('/communes/{arrondissementId}', [LocationController::class, 'getCommunes']);


require __DIR__.'/auth.php';
