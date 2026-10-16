<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;

class StoreUsuarioRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('create', User::class) ?? false;
    }

    public function rules(): array
    {
        $empresaId = $this->user()?->empresa_id;

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'perfil_acesso_id' => [
                'nullable',
                Rule::exists('perfis_acesso', 'id')->where('empresa_id', $empresaId),
            ],
            'role' => ['nullable', 'in:gerente,funcionario'],
        ];
    }
}
