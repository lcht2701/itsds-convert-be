<?php

namespace App\Http\Requests;

use App\Enums\TaskStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTicketTaskRequest extends FormRequest
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
            'title' => ['required', 'string', 'max:1024'],
            'description' => ['nullable', 'string', 'max:5000'],
            'note' => ['nullable', 'string', 'max:5000'],
            'priority' => ['required', Rule::enum(TaskStatus::class)],
            'start_time' => ['sometimes', 'required', 'date'],
            'end_time' => ['sometimes', 'nullable', 'date', 'after:start_time'],
            'progress' => ['sometimes', 'numeric', 'between:0,100'],
            'task_status' => ['sometimes', 'required', Rule::enum(TaskStatus::class)],

        ];
    }
}
