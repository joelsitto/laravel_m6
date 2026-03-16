<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjecteController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\ComentariController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('projectes.index');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {

    // Perfil (Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Projectes
    Route::prefix('projectes')->name('projectes.')->group(function () {
        Route::get('/',                       [ProjecteController::class, 'index'])->name('index');
        Route::get('/create',                 [ProjecteController::class, 'create'])->name('create');
        Route::post('/',                      [ProjecteController::class, 'store'])->name('store');
        Route::get('/{projecte}',             [ProjecteController::class, 'show'])->name('show');
        Route::get('/{projecte}/edit',        [ProjecteController::class, 'edit'])->name('edit');
        Route::put('/{projecte}',             [ProjecteController::class, 'update'])->name('update');
        Route::patch('/{projecte}/estat',     [ProjecteController::class, 'canviarEstat'])->name('canviarEstat');

        Route::prefix('{projecte}/tickets')->name('tickets.')->group(function () {
            Route::get('/',                    [TicketController::class, 'index'])->name('index');
            Route::get('/create',              [TicketController::class, 'create'])->name('create');
            Route::post('/',                   [TicketController::class, 'store'])->name('store');
            Route::get('/{ticket}',            [TicketController::class, 'show'])->name('show');
            Route::get('/{ticket}/edit',       [TicketController::class, 'edit'])->name('edit');
            Route::put('/{ticket}',            [TicketController::class, 'update'])->name('update');
        });
    });

    Route::prefix('tickets/{ticket}/comentaris')->name('tickets.comentaris.')->group(function () {
        Route::post('/',                       [ComentariController::class, 'store'])->name('store');
    });

    Route::delete('comentaris/{comentari}',   [ComentariController::class, 'destroy'])->name('comentaris.destroy');

    // Clients
    Route::prefix('clients')->name('clients.')->group(function () {
        Route::get('/',                       [ClientController::class, 'index'])->name('index');
        Route::get('/create',                 [ClientController::class, 'create'])->name('create');
        Route::post('/',                      [ClientController::class, 'store'])->name('store');
        Route::get('/{client}',               [ClientController::class, 'show'])->name('show');
        Route::get('/{client}/edit',          [ClientController::class, 'edit'])->name('edit');
        Route::put('/{client}',               [ClientController::class, 'update'])->name('update');
    });

});

require __DIR__.'/auth.php';
