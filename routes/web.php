<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\VeiculoController;
use App\Http\Controllers\PecaController;
use App\Http\Controllers\OrdemServicoController;
use App\Http\Controllers\OrdemServicoItemController;
use App\Http\Controllers\ServicoController;
use Illuminate\Support\Facades\Route;
use App\Models\Cliente;
use App\Models\Veiculo;
use App\Models\OrdemServico;
use App\Models\Servico;


Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', function () {

    return view('dashboard', [
        'clientes' => Cliente::count(),
        'veiculos' => Veiculo::count(),
        'ordens' => OrdemServico::count(),
        'servicos' => Servico::count(),
    ]);

})->name('dashboard');

    Route::resource('clientes', ClienteController::class);
    Route::resource('pecas', PecaController::class)
    ->middleware('auth');
    Route::resource('veiculos', VeiculoController::class)
    ->middleware('auth');
   Route::resource('ordens', OrdemServicoController::class)
    ->middleware('auth')
    ->parameters([
        'ordens' => 'ordem'
    ]);
    Route::post(
    '/ordens/{ordem}/itens',
    [OrdemServicoItemController::class, 'store']
)->name('ordens.itens.store');
Route::post(
    '/ordens/{ordem}/itens/peca',
    [OrdemServicoItemController::class, 'storePeca']
)->name('ordens.itens.peca.store');
    Route::resource('servicos', ServicoController::class)
    ->middleware('auth');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::get('/clientes/{cliente}/veiculos', function (\App\Models\Cliente $cliente) {
    return $cliente->veiculos;
})->middleware('auth');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

});

require __DIR__.'/auth.php';