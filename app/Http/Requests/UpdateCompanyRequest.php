<?php

namespace App\Http\Requests;

use App\Models\Company;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCompanyRequest extends FormRequest
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
            'company_name' => ['sometimes', 'required', 'string', 'max:255'],
            'tax_code' => ['nullable', 'string', 'min:10', 'max:13'],
            'company_website' => ['nullable'],
            'phone' => ['sometimes', 'required', 'string', 'regex:/^\d+$/', 'min:10', 'max:15'],
            'email' => ['sometimes', 'required', 'email', Rule::unique(Company::class)->ignore($this->route('company'))],
            'logo' => ['image'],
            'field_of_business' => ['nullable', 'string', 'max:255'],
            'is_active' => ['boolean']
        ];
    }
}
