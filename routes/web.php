<?php

use App\Http\Controllers\CommentaireController;
use App\Http\Controllers\DepannageController;
use App\Http\Controllers\PriseEnChargeController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';


Route::get('/', function () {
    return Inertia::render('Home');
});


Route::get('/takeit', [\App\Http\Controllers\TakeitController::class, 'index']);

Route::get('/takeit/dashboard', [\App\Http\Controllers\TakeitController::class, 'dashboard']);


Route::get('/api/clients', [\App\Http\Controllers\TakeitController::class, 'search']);
Route::post('/api/clients', [\App\Http\Controllers\TakeitController::class, 'createClient']);
Route::post('/api/prises-en-charge', [\App\Http\Controllers\TakeitController::class, 'store']);
Route::post('/api/materiels', [\App\Http\Controllers\TakeitController::class, 'storeMateriel']);
Route::get('/api/materiels/{clientId}', [\App\Http\Controllers\TakeitController::class, 'getMateriels']);
Route::get('/api/services', fn() => DB::table('lara_services')->get());
Route::post('/api/prises/{id}/commentaire', [TakeitController::class, 'addComment']);
Route::patch('/api/prises/{id}/statut', [TakeitController::class, 'updateStatut']);


Route::get('/api/prises/{id}/commentaires', function ($id) {
    return DB::table('lara_commentaires')
        ->where('prise_en_charge_id', $id)
        ->orderByDesc('created_at')
        ->get();
});

Route::get('/prises/{prise}/commentaires', [CommentaireController::class, 'index']);
Route::post('/prises/{prise}/commentaire', [CommentaireController::class, 'store']);




Route::get('/depannages', [DepannageController::class, 'index'])->name('depannages.index');
Route::get('/depannages/{id}', [DepannageController::class, 'show'])->name('depannages.show');

Route::get('/api/client/{id}', function ($id) {
    return DB::table('llx_societe')
        ->select('rowid', 'nom', 'address', 'zip', 'town', 'phone', 'email', 'code_client')
        ->where('rowid', $id)
        ->first();
});


