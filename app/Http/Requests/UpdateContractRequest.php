<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateContractRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'contract_num' => ['sometimes', 'required', 'string'],
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1024'],
            'company_id' => ['sometimes', 'required', 'exists:companies,id'],
            'start_date' => ['sometimes', 'required', 'date'],
            'duration' => ['sometimes', 'required', 'in:3,6,9,12,18,24,36'],
            'value' => ['sometimes', 'required', 'numeric', 'min:1000'],
        ];
    }
}
