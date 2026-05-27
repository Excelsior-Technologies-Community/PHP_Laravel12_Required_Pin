<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PinController;
use App\Http\Controllers\DashboardController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// HOME
Route::get('/', fn () => view('welcome'));


// =========================
// PIN SYSTEM
// =========================

// SHOW PIN PAGE
Route::get('/pin/required', [PinController::class, 'show'])
    ->name('pin.required');

// VERIFY PIN
Route::post('/pin/required', [PinController::class, 'verify'])
    ->name('pin.verify');

// CHANGE PIN
Route::post('/pin/change', [PinController::class, 'changePin'])
    ->name('changePinWeb');


// =========================
// AUTHENTICATED ROUTES
// =========================

Route::middleware(['auth', 'auto.logout'])->group(function () {

    // DASHBOARD
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    // PROFILE
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');
});


// AUTH ROUTES
require __DIR__.'/auth.php';