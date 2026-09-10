<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UploadFotoOrdemServicoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'fotos'   => 'required|array',
            'fotos.*' => 'image|mimes:jpeg,png,jpg,webp,gif|max:10240',
        ];
    }
}
