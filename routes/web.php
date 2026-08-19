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
use App\Http\Controllers\WebhookController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\SubscriptionController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| Rotas Públicas & Webhooks
|--------------------------------------------------------------------------
*/
Route::post('/webhooks/mercadopago', [WebhookController::class, 'handle'])->name('webhooks.mercadopago');

Route::get('/aprovacao/{token}', [AprovacaoController::class, 'show'])->name('aprovacao.show');
Route::post('/aprovacao/{token}/aprovar', [AprovacaoController::class, 'approve'])->name('aprovacao.approve');
Route::post('/aprovacao/{token}/reprovar', [AprovacaoController::class, 'reject'])->name('aprovacao.reject');

// Área pública de planos e contratação
Route::get('/planos', function () {
    return view('planos.index');
})->name('planos.index');

Route::get('/assinar', function () {
    if (auth()->check()) {
        return redirect()->route('checkout.show');
    }
    return redirect()->route('register');
})->name('planos.assinar');

/*
|--------------------------------------------------------------------------
| Rotas Autenticadas (Acessíveis mesmo com assinatura pendente)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    Route::get('/assinatura/pendente', function () {
        if (auth()->user()->empresa && auth()->user()->empresa->isAtiva()) {
            return redirect()->route('dashboard');
        }
        return view('planos.pendente');
    })->name('assinatura.pendente');

    // Endpoint de polling para verificar se a assinatura foi ativada (usado pela página pendente)
    Route::get('/assinatura/status', function () {
        $empresa = auth()->user()->empresa;
        if (!$empresa) {
            return response()->json(['ativa' => false, 'status' => 'sem_empresa']);
        }
        $empresa->refresh();
        $assinatura = $empresa->assinaturaAtiva()->first();
        return response()->json([
            'ativa'   => $empresa->isAtiva(),
            'status'  => $assinatura?->status ?? 'pending',
            'plano'   => $empresa->plano?->nome,
        ]);
    })->name('assinatura.status');

    // Minha Assinatura (gestão da assinatura dentro do SaaS)
    Route::get('/minha-assinatura', [SubscriptionController::class, 'index'])->name('assinatura.minha');

    Route::get('/checkout/{plano:slug?}', [CheckoutController::class, 'show'])->name('checkout.show');
    Route::post('/checkout/processar', [CheckoutController::class, 'processarPagamento'])->name('checkout.processar');
    Route::get('/planos/callback', [CheckoutController::class, 'callback'])->name('planos.callback');
    Route::post('/assinatura/cancelar', [SubscriptionController::class, 'cancelar'])->name('assinatura.cancelar');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| Rotas Operacionais do SaaS (Requer Empresa Ativa e Assinatura Válida)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'empresa.ativa'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/empresa', [EmpresaController::class, 'edit'])->name('empresa.edit');
    Route::put('/empresa', [EmpresaController::class, 'update'])->name('empresa.update');

    Route::get('/usuarios/create', [UsuarioController::class, 'create'])->name('usuarios.create');
    Route::post('/usuarios', [UsuarioController::class, 'store'])->name('usuarios.store');

    Route::resource('clientes', ClienteController::class);
    Route::resource('pecas', PecaController::class);
    Route::resource('veiculos', VeiculoController::class);
    Route::resource('servicos', ServicoController::class);

    Route::resource('ordens', OrdemServicoController::class)->parameters([
        'ordens' => 'ordem'
    ]);

    Route::post('/ordens/{ordem}/itens', [OrdemServicoItemController::class, 'store'])->name('ordens.itens.store');
    Route::post('/ordens/{ordem}/itens/peca', [OrdemServicoItemController::class, 'storePeca'])->name('ordens.itens.peca.store');
    Route::delete('/ordens/itens/{item}', [OrdemServicoItemController::class, 'destroy'])->name('ordens.itens.destroy');
    Route::get('/ordens/{ordem}/pdf', [OrdemServicoController::class, 'pdf'])->name('ordens.pdf');
    Route::post('/ordens/{ordem}/solicitar-aprovacao', [OrdemServicoController::class, 'solicitarAprovacao'])->name('ordens.solicitar-aprovacao');

    Route::get('/clientes/{cliente}/veiculos', function (\App\Models\Cliente $cliente) {
        return $cliente->veiculos;
    });
});

require __DIR__.'/auth.php';