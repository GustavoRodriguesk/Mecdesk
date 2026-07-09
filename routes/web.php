<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\VeiculoController;
use App\Http\Controllers\PecaController;
use App\Http\Controllers\OrdemServicoController;
use App\Http\Controllers\OrdemServicoItemController;
use App\Http\Controllers\ServicoController;
use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\AprovacaoController;
use Illuminate\Support\Facades\Route;
use App\Models\Cliente;
use App\Models\Veiculo;
use App\Models\OrdemServico;
use App\Models\Servico;
use App\Models\Peca;


Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| Rotas Públicas — Aprovação de OS
|--------------------------------------------------------------------------
*/
Route::get('/aprovacao/{token}', [AprovacaoController::class, 'show'])->name('aprovacao.show');
Route::post('/aprovacao/{token}/aprovar', [AprovacaoController::class, 'approve'])->name('aprovacao.approve');
Route::post('/aprovacao/{token}/reprovar', [AprovacaoController::class, 'reject'])->name('aprovacao.reject');

Route::middleware(['auth'])->group(function () {
    Route::get(
    '/dashboard',
    [DashboardController::class, 'index']
)->name('dashboard');

    Route::get('/empresa', [EmpresaController::class, 'edit'])->name('empresa.edit');
    Route::put('/empresa', [EmpresaController::class, 'update'])->name('empresa.update');

    Route::get('/planos/upgrade', function () {
        return view('planos.upgrade');
    })->name('planos.upgrade');
    
    Route::get('/usuarios/create', [UsuarioController::class, 'create'])->name('usuarios.create');
    Route::post('/usuarios', [UsuarioController::class, 'store'])->name('usuarios.store');

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
    Route::delete(
    '/ordens/itens/{item}',
    [OrdemServicoItemController::class, 'destroy']
)->name('ordens.itens.destroy');
Route::get(
    '/ordens/{ordem}/pdf',
    [OrdemServicoController::class, 'pdf']
)->name('ordens.pdf');
Route::post(
    '/ordens/{ordem}/solicitar-aprovacao',
    [OrdemServicoController::class, 'solicitarAprovacao']
)->name('ordens.solicitar-aprovacao');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::get('/clientes/{cliente}/veiculos', function (\App\Models\Cliente $cliente) {
    return $cliente->veiculos;
})->middleware('auth');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


});

require __DIR__.'/auth.php';