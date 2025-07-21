<?php

use App\Http\Controllers\ArriveeController;
use App\Http\Controllers\DepartController;
use App\Http\Controllers\InstanceController; 
use App\Http\Controllers\Auth\AuthenticatedSessionController; 
use App\Http\Controllers\ProfileController; 
use Illuminate\Container\Attributes\Auth; 
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

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

Route::post('/logout', function () {
    \Illuminate\Support\Facades\Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/');
})->name('logout');



Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Route::get('/dossier/next', [DossierController::class, 'getNextDossier']);  
// Route::get('/departements/{regionId}', [LocationController::class, 'getDepartementsByRegion']);
// Route::get('/arrondissements/{departementId}', [LocationController::class, 'getArrondissementByDepartement']);
// Route::get('/communes/{arrondissementId}', [LocationController::class, 'getCommunesByArrondissement']);
// Route::resource('secretariat', SecretariatController::class);
// Route::resource('geometre', GeometreController::class);
// Route::resource('donnee', DonneeController::class);
// Route::resource('matriceCadastrale', MatriceCadastraleController::class);
// Route::get('/secretariat/create', [SecretariatController::class, 'create'])->name('secretariat.create');
// Route::get('/donnee/create', [DonneeController::class, 'create'])->name('donnee.create'); 
// Route::get('/secretariat/dernier-ordre/{annee}', [SecretariatController::class, 'dernierOrdre']);

  
// Route::post('dossier/verify', [GeometreController::class, 'verify'])->name('dossier.verify');
 
// Route::get('/secretariat', [SecretariatController::class, 'create'])->name('secretariat.create');
// Route::post('/secretariat', [SecretariatController::class, 'store'])->name('secretariat.store');
// Route::get('/geometre/create', [GeometreController::class, 'create'])->name('geometre.create'); 
// // Route::get('/geometre/create/{txt_num_dossier}', [GeometreController::class, 'create'])->name('geometre.create');

// Route::get('/dossier/last', function () {
//     $lastDossier = Dossier::latest('id')->first(); 
//     return response()->json([
//         'num_dossier' => $lastDossier ? $lastDossier->txt_num_dossier : ''
//     ]);
// });
// // code d'accée
// Route::post('/code/verify', [CodeAccesController::class, 'verify'])->name('code.verify'); 
// Route::get('/donnee/create', [DonneeController::class, 'create'])->name('donnee.create');
 
// Route::put('/secretariat/update/{terrain}', [SecretariatController::class, 'update'])->name('secretariat.update'); 
// Route::get('/secretariat/edit/{terrain}', [SecretariatController::class, 'edit'])->name('secretariat.edit'); 
// Route::delete('/terrains/{terrain}', [TerrainController::class, 'destroy'])->name('terrains.destroy');
 
 
//courierNouvelle appli
Route::resource('arrivee', ArriveeController::class);
Route::resource('depart', DepartController::class);
Route::resource('instance', InstanceController::class);
Route::get('/arrivee', [ArriveeController::class, 'create'])->name('arrivee.create');
Route::get('/depart', [DepartController::class, 'create'])->name('depart.create');
Route::get('/instance', [InstanceController::class, 'create'])->name('instance.create'); 
 
  

Route::get('/arrivee/next/{annee}', [ArriveeController::class, 'getNextOrdre']);  
Route::get('/depart/next/{annee}', [DepartController::class, 'getNextOrdre']);  
 


Route::get('/references-arrivee', [DepartController::class, 'fetchReferencesArrivee']);  
Route::post('/fetch-references-arrivee', [DepartController::class, 'fetchReferencesArrivee']); 


Route::post('/arrivee',[ArriveeController::class, 'store'])->name('arrivee.store');
Route::post('/depart',[DepartController::class, 'store'])->name('depart.store');

Route::get('/instance/create', [InstanceController::class, 'create'])->name('instance.create');   


Route::delete('/instance/arrivee/{id}', [InstanceController::class, 'destroy'])->name('instance.destroyArrivee');
Route::delete('/instance/depart/{id}', [InstanceController::class, 'destroyDepart'])->name('instance.destroyDepart'); 

Route::get('/arrivee/editarrivee/{id}', [ArriveeController::class, 'editarrivee'])->name('arrivee.editarrivee');
Route::get('/depart/editdepart/{id}', [DepartController::class, 'editdepart'])->name('depart.editdepart');

Route::put('/arrivee/update/{id}', [ArriveeController::class, 'update'])->name('arrivee.update'); 
Route::put('/depart/update/{id}', [DepartController::class, 'update'])->name('depart.update'); 

// Route::put('/secretariat/update/{terrain}', [SecretariatController::class, 'update'])->name('secretariat.update'); 


require __DIR__ . '/auth.php';
