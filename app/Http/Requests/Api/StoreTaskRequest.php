<?php

namespace App\Http\Requests\Api;

use App\Enum\TaskPriorityEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTaskRequest extends FormRequest
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
            'title' => ['required', 'max:255', 'string'],
            'description' => ['required', 'max:255', 'string'],
            'status' => ['required', 'max:4', 'string'],
            'priority' => ['required', 'max:255', 'int', Rule::in(TaskPriorityEnum::cases())],
            'completedAt' => ['sometimes', 'max:255', 'string'],
            'taskId' => ['sometimes', 'int', 'exists:App\Models\Task,id'],
        ];
    }

    public function validationData()
    {
        return array_merge($this->route()->parameters(), $this->all());
    }
}
