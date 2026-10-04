<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePecaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $merge = [];

        if (!$this->has('preco_custo')) {
            $merge['preco_custo'] = 0;
        } elseif (is_string($this->preco_custo)) {
            $merge['preco_custo'] = $this->sanitizeCurrency($this->preco_custo);
        }

        if ($this->has('preco_venda') && is_string($this->preco_venda)) {
            $val = $this->sanitizeCurrency($this->preco_venda);
            $merge['preco_venda'] = $val;
            $merge['valor_unitario'] = $val;
        } elseif ($this->has('valor_unitario') && is_string($this->valor_unitario)) {
            $val = $this->sanitizeCurrency($this->valor_unitario);
            $merge['valor_unitario'] = $val;
            $merge['preco_venda'] = $val;
        }

        if (!$this->has('estoque') || $this->estoque === null || $this->estoque === '') {
            $merge['estoque'] = 0;
        }

        if (!empty($merge)) {
            $this->merge($merge);
        }
    }

    private function sanitizeCurrency(string $value): string
    {
        $val = trim($value);
        $val = str_replace(['R$', ' '], '', $val);
        if (str_contains($val, ',') && str_contains($val, '.')) {
            $val = str_replace('.', '', $val);
            $val = str_replace(',', '.', $val);
        } elseif (str_contains($val, ',')) {
            $val = str_replace(',', '.', $val);
        }
        return $val;
    }

    public function rules(): array
    {
        $pecaId = $this->route('peca') instanceof \App\Models\Peca 
            ? $this->route('peca')->id 
            : $this->route('peca');

        return [
            'nome' => 'required|string|max:255',
            'marca' => 'nullable|string|max:255',
            'codigo' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('pecas', 'codigo')
                    ->ignore($pecaId)
                    ->where('empresa_id', auth()->user()->empresa_id)
                    ->whereNull('deleted_at'),
            ],
            'codigo_barras' => 'nullable|string|max:255',
            'preco_custo' => 'required|numeric|min:0',
            'preco_venda' => 'required_without:valor_unitario|nullable|numeric|min:0',
            'valor_unitario' => 'required_without:preco_venda|nullable|numeric|min:0',
            'estoque' => 'nullable|integer|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'nome.required' => 'O nome da peça é obrigatório.',
            'preco_custo.required' => 'O preço de custo é obrigatório.',
            'preco_custo.numeric' => 'O preço de custo deve ser um número válido.',
            'preco_custo.min' => 'O preço de custo não pode ser negativo.',
            'preco_venda.required_without' => 'O preço de venda é obrigatório.',
            'valor_unitario.required_without' => 'O preço de venda é obrigatório.',
            'codigo.unique' => 'Já existe uma peça cadastrada com este código interno.',
            'estoque.integer' => 'O estoque deve ser um número inteiro.',
            'estoque.min' => 'O estoque não pode ser negativo.',
        ];
    }
}
