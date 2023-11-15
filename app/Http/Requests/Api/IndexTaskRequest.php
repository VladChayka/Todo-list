<?php

namespace App\Http\Requests\Api;

use App\Filters\TaskFilter;
use App\Rules\CheckSortKeysRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class IndexTaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'search' => ['sometimes', 'string'],
            'filter' => ['sometimes', 'array'],
            'sort' => ['sometimes', 'array', new CheckSortKeysRule(TaskFilter::$sortKeys)],
            'sort.*' => ['sometimes', 'string', Rule::in(['asc', 'desc'])]
        ];
    }

    public function validationData()
    {
        return array_merge($this->route()->parameters(), $this->all());
    }
}
