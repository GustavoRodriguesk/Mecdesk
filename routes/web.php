<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\VeiculoController;
use App\Http\Controllers\OrdemServicoController;
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
   Route::resource('ordens', OrdemServicoController::class)
    ->middleware('auth')
    ->parameters([
        'ordens' => 'ordem'
    ]);
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::get('/clientes/{cliente}/veiculos', function (\App\Models\Cliente $cliente) {
    return $cliente->veiculos;
})->middleware('auth');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

});

require __DIR__.'/auth.php';