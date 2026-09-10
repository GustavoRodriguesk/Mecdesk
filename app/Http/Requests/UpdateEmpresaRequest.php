<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEmpresaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()?->canManageCompany() ?? false;
    }

    public function rules(): array
    {
        return [
            'nome_fantasia' => 'required|string|max:150',
            'razao_social'  => 'nullable|string|max:150',
            'cnpj'          => 'nullable|string|max:18',
            'email'         => 'nullable|email|max:100',
            'telefone'      => 'nullable|string|max:15',
            'whatsapp'      => 'nullable|string|max:15',
            'cep'           => 'nullable|string|max:9',
            'logradouro'    => 'nullable|string|max:100',
            'numero'        => 'nullable|string|max:8',
            'bairro'        => 'nullable|string|max:100',
            'cidade'           => 'nullable|string|max:50',
            'estado'           => 'nullable|string|max:2',
            'controle_estoque' => 'nullable|boolean',
            'logo'             => 'nullable|image|mimes:jpeg,png,jpg,webp,gif,svg|max:5120',
        ];
    }

    public function messages(): array
    {
        return [
            'logo.image' => 'O arquivo selecionado deve ser uma imagem vǭlida.',
            'logo.mimes' => 'O logotipo deve estar nos formatos: PNG, JPG, JPEG, WEBP, GIF ou SVG.',
            'logo.max'   => 'O logotipo nǜo pode ser maior que 5 MB.',
        ];
    }
}
