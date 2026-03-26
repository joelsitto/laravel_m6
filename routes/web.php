<?php

use App\Http\Controllers\ClientController;
use App\Http\Controllers\ComentariController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjecteController;
use App\Http\Controllers\ProjecteEquipController;
use App\Http\Controllers\RegistreTempsController;
use App\Http\Controllers\TicketController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('projectes.index');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::prefix('projectes')->name('projectes.')->group(function () {
        Route::get('/', [ProjecteController::class, 'index'])->name('index');
        Route::get('/{projecte}', [ProjecteController::class, 'show'])->whereNumber('projecte')->name('show');

        Route::prefix('{projecte}/tickets')->name('tickets.')->whereNumber('projecte')->group(function () {
            Route::get('/', [TicketController::class, 'index'])->name('index');
            Route::get('/create', [TicketController::class, 'create'])->name('create');
            Route::post('/', [TicketController::class, 'store'])->name('store');
            Route::get('/{ticket}', [TicketController::class, 'show'])->whereNumber('ticket')->name('show');
            Route::get('/{ticket}/edit', [TicketController::class, 'edit'])->whereNumber('ticket')->name('edit');
            Route::put('/{ticket}', [TicketController::class, 'update'])->whereNumber('ticket')->name('update');
        });
    });

    Route::prefix('tickets/{ticket}/comentaris')->whereNumber('ticket')->name('tickets.comentaris.')->group(function () {
        Route::post('/', [ComentariController::class, 'store'])->name('store');
    });
    Route::prefix('tickets/{ticket}/registres-temps')->whereNumber('ticket')->name('tickets.registresTemps.')->group(function () {
        Route::post('/', [RegistreTempsController::class, 'store'])->name('store');
    });
    Route::patch('tickets/{ticket}/estat', [TicketController::class, 'canviarEstat'])->whereNumber('ticket')->name('tickets.canviarEstat');

    Route::delete('comentaris/{comentari}', [ComentariController::class, 'destroy'])->whereNumber('comentari')->name('comentaris.destroy');
    Route::delete('registres-temps/{registreTemps}', [RegistreTempsController::class, 'destroy'])->whereNumber('registreTemps')->name('registresTemps.destroy');

    Route::get('clients/{client}', [ClientController::class, 'show'])->whereNumber('client')->name('clients.show');

    Route::middleware('role:ADMIN,GESTOR')->group(function () {
        Route::prefix('projectes')->name('projectes.')->group(function () {
            Route::get('/create', [ProjecteController::class, 'create'])->name('create');
            Route::post('/', [ProjecteController::class, 'store'])->name('store');
            Route::get('/{projecte}/edit', [ProjecteController::class, 'edit'])->whereNumber('projecte')->name('edit');
            Route::put('/{projecte}', [ProjecteController::class, 'update'])->whereNumber('projecte')->name('update');
            Route::patch('/{projecte}/estat', [ProjecteController::class, 'canviarEstat'])->whereNumber('projecte')->name('canviarEstat');

            Route::prefix('{projecte}/equip')->name('equip.')->whereNumber('projecte')->group(function () {
                Route::get('/', [ProjecteEquipController::class, 'index'])->name('index');
                Route::post('/', [ProjecteEquipController::class, 'store'])->name('store');
                Route::delete('/{user}', [ProjecteEquipController::class, 'destroy'])->whereNumber('user')->name('destroy');
            });

            Route::patch('{projecte}/tickets/{ticket}/assignar', [TicketController::class, 'assignar'])
                ->whereNumber('projecte')
                ->whereNumber('ticket')
                ->name('tickets.assignar');
        });

        Route::prefix('clients')->name('clients.')->group(function () {
            Route::get('/', [ClientController::class, 'index'])->name('index');
            Route::get('/create', [ClientController::class, 'create'])->name('create');
            Route::post('/', [ClientController::class, 'store'])->name('store');
            Route::get('/{client}/edit', [ClientController::class, 'edit'])->whereNumber('client')->name('edit');
            Route::put('/{client}', [ClientController::class, 'update'])->whereNumber('client')->name('update');
        });
    });

});

require __DIR__.'/auth.php';
