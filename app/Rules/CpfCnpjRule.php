<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class CpfCnpjRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (empty($value)) {
            return;
        }

        // Remove non-digit characters
        $c = preg_replace('/\D/', '', (string) $value);

        if (strlen($c) === 11) {
            if (!$this->validarCpf($c)) {
                $fail('O campo :attribute deve conter um CPF válido.');
            }
        } elseif (strlen($c) === 14) {
            if (!$this->validarCnpj($c)) {
                $fail('O campo :attribute deve conter um CNPJ válido.');
            }
        } else {
            $fail('O campo :attribute deve ser um CPF (11 dígitos) ou CNPJ (14 dígitos) válido.');
        }
    }

    private function validarCpf(string $cpf): bool
    {
        if (preg_match('/^(\d)\1{10}$/', $cpf)) {
            return false;
        }

        for ($t = 9; $t < 11; $t++) {
            for ($d = 0, $c = 0; $c < $t; $c++) {
                $d += (int) $cpf[$c] * (($t + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;
            if ((int) $cpf[$c] !== $d) {
                return false;
            }
        }
        return true;
    }

    private function validarCnpj(string $cnpj): bool
    {
        if (preg_match('/^(\d)\1{13}$/', $cnpj)) {
            return false;
        }

        $b = [6, 5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];

        for ($i = 0, $n = 0; $i < 12; $i++) {
            $n += (int) $cnpj[$i] * $b[$i + 1];
        }
        if ((int) $cnpj[12] !== (($n %= 11) < 2 ? 0 : 11 - $n)) {
            return false;
        }

        for ($i = 0, $n = 0; $i < 13; $i++) {
            $n += (int) $cnpj[$i] * $b[$i];
        }
        if ((int) $cnpj[13] !== (($n %= 11) < 2 ? 0 : 11 - $n)) {
            return false;
        }

        return true;
    }
}
