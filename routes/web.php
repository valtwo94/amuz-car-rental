<?php

use App\Http\Controllers\CarController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReservationController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('/', [CarController::class, 'list'])->name('list');


Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->middleware(['auth', 'verified'])->name('dashboard');
    Route::get('/reservation', [ReservationController::class, 'create'])->name('reservation');
    Route::get('/create', [CarController::class, 'create'])->name('create');
    Route::post('/create', [CarController::class, 'store'])->name('cars.store');
    Route::post('/reservation', [ReservationController::class, 'store'])->name('reservation.store');
    Route::post('/reservation/check', [ReservationController::class, 'reservationCheck'])->name('reservation.check');
    Route::post('/reservation/update', [ReservationController::class, 'reservationUpdate'])->name('reservation.update');
    Route::patch('/reservation', [ReservationController::class, 'updateToFixedReservation'])->name('reservation.update-to-fixed-reservation');
    Route::get('/show/{id}', [CarController::class, 'show'])->name('show');
});


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
