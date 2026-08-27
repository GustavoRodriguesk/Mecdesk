<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class PlacaVeiculoRule implements ValidationRule
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

        $placa = strtoupper(trim((string) $value));

        // Formato tradicional: ABC-1234 ou ABC1234
        // Formato Mercosul: ABC1D23 ou ABC-1D23
        $pattern = '/^[A-Z]{3}-?(\d{4}|\d[A-Z]\d{2})$/i';

        if (!preg_match($pattern, $placa)) {
            $fail('O campo :attribute deve conter uma placa válida (ex: ABC-1234 ou ABC1D23).');
        }
    }
}
