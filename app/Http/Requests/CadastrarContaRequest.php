<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules;

class CadastrarContaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'empresa' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'telefone' => ['nullable', 'string', 'max:50'],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ];
    }

    public function messages(): array
    {
        return [
            'empresa.required' => 'O nome da oficina Ǹ obrigatrio.',
            'name.required' => 'O seu nome completo Ǹ obrigatrio.',
            'email.required' => 'O e-mail Ǹ obrigatrio.',
            'email.email' => 'Informe um endereo de e-mail vǭlido.',
            'email.unique' => 'Este e-mail jǭ estǭ cadastrado em nosso sistema.',
            'password.required' => 'A senha Ǹ obrigatria.',
            'password.confirmed' => 'A confirmaǜo de senha nǜo confere.',
        ];
    }
}
