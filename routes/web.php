<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\B2BPortalController;
use App\Http\Controllers\VehicleController;

/*
|--------------------------------------------------------------------------
| Web Routes - Alt Motors B2B
|--------------------------------------------------------------------------
*/

Route::get('/', [B2BPortalController::class, 'index'])->name('home');
Route::get('/espace-entreprise', [B2BPortalController::class, 'index'])->name('b2b.portal');
Route::get('/entreprises', [B2BPortalController::class, 'index']);

Route::get('/fleet/live-tracking', [VehicleController::class, 'index'])->name('fleet.index');
Route::get('/fleet', [VehicleController::class, 'index']);
Route::patch('/vehicles/{id}/coordinates', [VehicleController::class, 'updateCoordinates'])->name('fleet.coordinates.update');

// Route pour traiter et enregistrer la demande de devis LLD du simulateur
Route::post('/espace-entreprise/devis', [B2BPortalController::class, 'storeQuote'])->name('b2b.quote.store');