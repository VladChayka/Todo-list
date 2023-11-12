<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class CheckSortKeysRule implements ValidationRule
{
    public function __construct(public array $keys)
    {

    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $difference = array_diff(array_keys($value), $this->keys);
        if (count($difference) > 0) {
            $fail(__('validation.in', ['attribute' => $difference[0]]));
        }
    }
}
