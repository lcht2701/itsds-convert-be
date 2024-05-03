<?php

namespace App\Http\Requests;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class UpdateUserRequest extends FormRequest
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
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'email' => ['sometimes', 'required', 'email', Rule::unique(User::class, 'email')->ignore($this->route('user'))],
            'password' => [
                'nullable',
                'string',
                'min:8',
                'regex:/[0-9]/',
                'regex:/[a-zA-Z]/',
            ],
            'role' => [new Enum(UserRole::class)],
            'phone' => ['sometimes', 'required', 'string', 'regex:/^\d+$/', 'min:10', 'max:15'],
            'is_active' => ['sometimes', 'required', 'boolean']
        ];
    }
}
