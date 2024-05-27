<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreContractRequest extends FormRequest
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
            'contract_num' => ['required', 'string'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1024'],
            'company_id' => ['required', 'exists:companies,id'],
            'start_date' => ['required', 'date'],
            'duration' => ['required', 'in:3,6,9,12,18,24,36'],
            'value' => ['required', 'numeric', 'min:1000'],
        ];
    }
}
