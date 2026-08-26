<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;

class NotSequentialNumbers implements ValidationRule
{
    protected int $sequenceLength;

    /**
     * @param  int  $sequenceLength  Tamanho mínimo da sequência numérica para ser considerada inválida (padrão: 3)
     */
    public function __construct(int $sequenceLength = 3)
    {
        $this->sequenceLength = $sequenceLength;
    }

    /**
     * Run the validation rule.
     *
     * @param  Closure(string, ?string=): PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! is_string($value) || strlen($value) < $this->sequenceLength) {
            return;
        }

        if ($this->hasSequentialNumbers($value)) {
            $fail('A senha não pode conter números sequenciais (ex: 12345678).');
        }
    }

    /**
     * Verifica se a string contém uma sequência de números consecutivos (crescentes, decrescentes ou repetidos).
     */
    public function hasSequentialNumbers(string $value): bool
    {
        $len = strlen($value);
        $seqCountAsc = 1;
        $seqCountDesc = 1;
        $seqCountSame = 1;

        for ($i = 1; $i < $len; $i++) {
            $prev = $value[$i - 1];
            $curr = $value[$i];

            if (ctype_digit($prev) && ctype_digit($curr)) {
                $prevNum = (int) $prev;
                $currNum = (int) $curr;

                // Sequência crescente (+1), ex: 123, 1234, 12345678
                if ($currNum === $prevNum + 1) {
                    $seqCountAsc++;
                    if ($seqCountAsc >= $this->sequenceLength) {
                        return true;
                    }
                } else {
                    $seqCountAsc = 1;
                }

                // Sequência decrescente (-1), ex: 321, 4321, 87654321
                if ($currNum === $prevNum - 1) {
                    $seqCountDesc++;
                    if ($seqCountDesc >= $this->sequenceLength) {
                        return true;
                    }
                } else {
                    $seqCountDesc = 1;
                }

                // Sequência de dígitos repetidos, ex: 1111, 0000
                if ($currNum === $prevNum) {
                    $seqCountSame++;
                    if ($seqCountSame >= max(4, $this->sequenceLength)) {
                        return true;
                    }
                } else {
                    $seqCountSame = 1;
                }
            } else {
                $seqCountAsc = 1;
                $seqCountDesc = 1;
                $seqCountSame = 1;
            }
        }

        return false;
    }
}
