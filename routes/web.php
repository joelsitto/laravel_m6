<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjecteController;
use App\Http\Controllers\LlibresController;
use App\Http\Controllers\PrestecsController;
use App\Http\Controllers\ClientController;
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

    // Clients
    Route::prefix('clients')->name('clients.')->group(function () {
        Route::get('/',                       [ClientController::class, 'index'])->name('index');
        Route::get('/create',                 [ClientController::class, 'create'])->name('create');
        Route::post('/',                      [ClientController::class, 'store'])->name('store');
        Route::get('/{client}',               [ClientController::class, 'show'])->name('show');
        Route::get('/{client}/edit',          [ClientController::class, 'edit'])->name('edit');
        Route::put('/{client}',               [ClientController::class, 'update'])->name('update');
        Route::get('/{client}/projectes',     [ClientController::class, 'projectes'])->name('projectes');
    });

    Route::prefix('llibres')->name('llibres.')->group(function () {
        Route::get('/create', [LlibresController::class, 'create'])->name('create');
        Route::post('/create', [LlibresController::class, 'store'])->name('store');
        Route::get('/cataleg', [LlibresController::class, 'index'])->name('index');
        Route::get('/senseprestecs', [LlibresController::class, 'sensePrestecs'])->name('senseprestecs');
        Route::get('/eliminar/{llibres}', [LlibresController::class, 'delete'])->name('delete');
        Route::post('/modificar/{llibres}', [LlibresController::class, 'update'])->name('update');
        Route::get('/modificar/{llibres}', [LlibresController::class, 'edit'])->name('edit');
    });

    Route::get('/bibliotecari/assignar/{idLlibre}/{idBibliotecari}', [LlibresController::class, 'assignarBibliotecari'])
        ->name('bibliotecari.assignar');

    Route::prefix('prestecs')->name('prestecs.')->group(function () {
        Route::get('/{llibre}/{user}', [PrestecsController::class, 'create'])->name('create');
    });

    Route::get('/retornar/{idPrestec}', [PrestecsController::class, 'retornar'])->name('prestecs.retornar');

    Route::prefix('projecte')->name('projecte.')->group(function () {
        Route::get('/create', [ProjecteController::class, 'create'])->name('create');
        Route::post('/create', [ProjecteController::class, 'store'])->name('store');
        Route::get('/{projecte}', [ProjecteController::class, 'show'])->name('show');
        Route::get('/nocheckpoints', [ProjecteController::class, 'nocheckpoints'])->name('nocheckpoints');
        Route::get('/delete/{projecte}', [ProjecteController::class, 'delete'])->name('delete');
    });


});

require __DIR__.'/auth.php';
