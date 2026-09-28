<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AppController;
use App\Http\Controllers\DepotController;
use App\Http\Controllers\GerantController;
use Laravel\Fortify\Http\Controllers\RegisteredUserController;

// Route::get('/', function () {
//     return view('welcome');
// });
Route::get ('/',[AppController::class,'index'])->name('welcome')->middleware(['guest']);
Route::get ('/dashboard',[AppController::class,'testuser'])->name('dashboard');
Route::get ('/signaler_document_perdu',[AppController::class,'signaler'])->name('signaler');
Route::post ('/signaler_document_perdu',[AppController::class,'store_lost_document'])->name('store_lost_document');
Route::get('/user/documents/signalés', [AppController::class,'docs_signaler'])->name('docs_signales');
Route::get('/user/documents/perdus', [AppController::class,'docs_perdu'])->name('docs_perdus');
Route::get('/user/documents/trouves', [AppController::class,'docs_trouver'])->name('docs_trouves');
Route::delete('/document/delete{id}', [AppController::class,'supprimer_doc'])->name('supp_doc');
Route::get('/documents', [AppController::class, 'index1'])->name('documents.index');
Route::get('/documentstrouves', [AppController::class, 'index_found'])->name('documents.index.found');

Route::resource('depot', DepotController::class);
Route::resource('gerant', GerantController::class);

// Route::get ('/login',[AppController::class,'connexion'])->name('login'); cette route est gerer automatiquement pas fortify

Route::get ('/search',[AppController::class,'testuser'])->name('search')->middleware(['guest']);


Route::get('/user/documents', function () {
    return 'Page des documents - encore en développement.';
})->name('user.documents');

Route::get('/user/profile', function () {
    return 'Page de profil utilisateur - encore en développement.';
})->name('user.profile');

Route::get('/user/history', function () {
    return 'Historique des documents retrouvés - encore en développement.';
})->name('user.history');

Route::get('/user/report_document', function () {
    return 'Page de signalement de document - encore en développement.';
})->name('user.report_document');