<?php

namespace App\Http\Requests\Api;


class UpdateTaskRequest extends StoreTaskRequest
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
        $parentRules = parent::rules();

        unset($parentRules['status']);

        return array_merge($parentRules);
    }

    public function validationData()
    {
        return array_merge($this->route()->parameters(), $this->all());
    }
}
