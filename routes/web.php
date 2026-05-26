<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PinController;

Route::get('/', fn () => view('welcome'));

// PIN SYSTEM
Route::get('/pin/required', [PinController::class, 'show'])
    ->name('pin.required');

Route::post('/pin/required', [PinController::class, 'verify'])
    ->name('pin.verify');

// CHANGE PIN
Route::post('/pin/change', [PinController::class, 'changePin'])
    ->name('changePinWeb');

// DASHBOARD (NO middleware system)
Route::get('/dashboard', function () {

    if (!session('pin_verified')) {
        return redirect()->route('pin.required');
    }

    return view('dashboard');

})->middleware(['auth'])->name('dashboard');

// PROFILE
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';