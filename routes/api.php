<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;

use App\Http\Controllers\MedecinController;
use App\Http\Controllers\SpecialiteController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('/register', [RegisteredUserController::class, 'store']);
Route::post('/login', [AuthenticatedSessionController::class, 'store']);
// Route pour afficher le formulaire de demande de réinitialisation
Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
    ->middleware('guest')
    ->name('password.request');

// Route pour envoyer le lien de réinitialisation
Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])
    ->middleware('guest')
    ->name('password.email');

// Route pour afficher le formulaire de réinitialisation de mot de passe
Route::get('/password-reset/{token}', [NewPasswordController::class, 'create'])
    ->middleware('guest')
    ->name('password.reset');

// Route pour mettre à jour le mot de passe
Route::post('/password-reset', [NewPasswordController::class, 'store'])
    ->middleware('guest')
    ->name('password.update');
Route::get('/specialites/search', [\App\Http\Controllers\SpecialiteController::class,'search']);

// Route pour obtenir toutes les spécialités
Route::get('/specialites', [SpecialiteController::class, 'index']);


Route::get('/medecins', [MedecinController::class, 'index']);
