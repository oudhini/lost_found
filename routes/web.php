<?php

use App\Http\Controllers\Admin\DocumentController as AdminDocumentController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\AppController;
use App\Http\Controllers\DepotController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\GerantController;
use App\Http\Controllers\Manager\DocumentController as ManagerDocumentController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;
use App\Models\User;
use Illuminate\Support\Facades\Route;

// Login / registration / password routes are registered by Fortify.

Route::get('/', [AppController::class, 'index'])->name('welcome')->middleware('guest');
Route::get('/search', [AppController::class, 'testuser'])->name('search')->middleware('guest');

Route::middleware(['auth', 'active'])->group(function () {

    /*
    |----------------------------------------------------------------------
    | Supervisor only
    |----------------------------------------------------------------------
    | Registered BEFORE the shared depot routes so that /depot/create is
    | never captured by /depot/{depot}.
    */
    Route::middleware('role:'.User::ROLE_SUPERVISOR)->group(function () {
        Route::resource('depot', DepotController::class)->except(['index', 'show']);
        Route::resource('gerant', GerantController::class);

        Route::prefix('admin')->name('admin.')->group(function () {
            Route::get('documents/export', [AdminDocumentController::class, 'export'])->name('documents.export');
            Route::get('documents', [AdminDocumentController::class, 'index'])->name('documents.index');
            Route::get('documents/{document}', [AdminDocumentController::class, 'show'])->name('documents.show');
            Route::patch('documents/{document}/depot', [AdminDocumentController::class, 'assignDepot'])->name('documents.assign-depot');
            Route::patch('documents/{document}/restitution', [AdminDocumentController::class, 'restitute'])->name('documents.restitute');
            Route::delete('documents/{document}', [AdminDocumentController::class, 'destroy'])->name('documents.destroy');

            Route::get('users', [AdminUserController::class, 'index'])->name('users.index');
            Route::patch('users/{user}/toggle-active', [AdminUserController::class, 'toggleActive'])->name('users.toggle-active');
        });

    });

    /*
    |----------------------------------------------------------------------
    | Depot manager only. Everything except the dashboard requires an assigned depot.
    |----------------------------------------------------------------------
    */
    Route::middleware('role:'.User::ROLE_MANAGER)->prefix('espace-gerant')->name('manager.')->group(function () {
        Route::middleware('has.depot')->group(function () {
            Route::get('documents', [ManagerDocumentController::class, 'index'])->name('documents.index');
            Route::get('documents/{document}', [ManagerDocumentController::class, 'show'])->whereNumber('document')->name('documents.show');
            Route::patch('documents/{document}/restitution', [ManagerDocumentController::class, 'restitute'])->whereNumber('document')->name('documents.restitute');
            Route::get('reception', [ManagerDocumentController::class, 'receiveForm'])->name('receive');
            Route::patch('reception/{document}', [ManagerDocumentController::class, 'receive'])->whereNumber('document')->name('receive.store');
            Route::get('trouve/nouveau', [ManagerDocumentController::class, 'foundCreate'])->name('found.create');
            Route::post('trouve', [ManagerDocumentController::class, 'foundStore'])->name('found.store');
            Route::get('historique', [ManagerDocumentController::class, 'history'])->name('history');
        });
    });

    /*
    |----------------------------------------------------------------------
    | Any authenticated (and active) user
    |----------------------------------------------------------------------
    */
    Route::get('/dashboard', [AppController::class, 'testuser'])->name('dashboard');
    Route::get('/signaler_document_perdu', [AppController::class, 'signaler'])->name('signaler');
    Route::post('/signaler_document_perdu', [AppController::class, 'store_lost_document'])->name('store_lost_document');
    Route::get('/user/documents/signalés', [AppController::class, 'docs_signaler'])->name('docs_signales');
    Route::get('/user/documents/perdus', [AppController::class, 'docs_perdu'])->name('docs_perdus');
    Route::get('/user/documents/trouves', [AppController::class, 'docs_trouver'])->name('docs_trouves');
    Route::delete('/document/delete{id}', [AppController::class, 'supprimer_doc'])->name('supp_doc');
    Route::get('/documents', [AppController::class, 'index1'])->name('documents.index');
    Route::get('/documentstrouves', [AppController::class, 'index_found'])->name('documents.index.found');

    Route::resource('depot', DepotController::class)->only(['index', 'show']);

    Route::get('/user/profile', [ProfileController::class, 'show'])->name('user.profile');

    // Shared read-only document detail page (own reports, lost board, found board).
    Route::get('/documents/{document}', [DocumentController::class, 'show'])->whereNumber('document')->name('documents.show');

    /*
    |----------------------------------------------------------------------
    | In-app notifications: every authenticated, active user has their own.
    |----------------------------------------------------------------------
    */
    Route::get('notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::patch('notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');
    Route::patch('notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
});
