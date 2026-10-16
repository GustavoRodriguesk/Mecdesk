<?php

namespace App\Providers;

use App\Models\User;
use App\Rules\NotSequentialNumbers;
use App\Support\Permissoes;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Password::defaults(function () {
            return Password::min(8)
                ->rules([new NotSequentialNumbers()]);
        });

        // Um Gate por permissão do catálogo: $user->can('clientes.criar'), @can(...), can:...
        foreach (Permissoes::slugs() as $slug) {
            Gate::define($slug, fn (User $user) => $user->temPermissao($slug));
        }
    }
}
