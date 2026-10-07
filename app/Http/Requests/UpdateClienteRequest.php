<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateClienteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('possui_whatsapp')) {
            $this->merge([
                'possui_whatsapp' => filter_var($this->possui_whatsapp, FILTER_VALIDATE_BOOLEAN),
            ]);
        }
    }

    public function rules(): array
    {
        return [
            'nome'            => 'required|string|max:100',
            'telefone'        => 'required|string',
            'telefone_2'      => 'nullable|string',
            'telefone_3'      => 'nullable|string',
            'possui_whatsapp' => 'nullable|boolean',
            'email'           => 'nullable|email|max:100',
            'instagram'       => 'nullable|string|max:100',
            'cpf_cnpj'        => ['nullable', new \App\Rules\CpfCnpjRule],
            'cep'             => 'nullable|string|max:10',
            'rua'             => 'nullable|string|max:255',
            'numero'          => 'nullable|string|max:20',
            'complemento'     => 'nullable|string|max:100',
            'bairro'          => 'nullable|string|max:100',
            'cidade'          => 'nullable|string|max:100',
            'estado'          => 'nullable|string|max:2',
            'endereco'        => 'nullable|string',
        ];
    }
}
