<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\VeiculoController;
use Illuminate\Support\Facades\Route;
use App\Models\Cliente;
use App\Models\Veiculo;
Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', function () {

    return view('dashboard', [
        'clientes' => Cliente::count(),
        'veiculos' => Veiculo::count(),
    ]);

})->name('dashboard');

    Route::resource('clientes', ClienteController::class);
    Route::resource('veiculos', VeiculoController::class)
    ->middleware('auth');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

});

require __DIR__.'/auth.php';