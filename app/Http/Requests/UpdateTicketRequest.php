<?php

namespace App\Http\Requests;

use App\Enums\Priority;
use App\Enums\TicketImpact;
use App\Enums\TicketType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTicketRequest extends FormRequest
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
            'requester_id' => ['required', 'exists:users,id'],
            'service_id' => ['required', 'exists:services,id'],
            'title' => ['required', 'string', 'max:1024'],
            'description' => ['nullable', 'string', 'max:5000'],
            'priority' => ['required', Rule::enum(Priority::class)],
            'impact' => ['required', Rule::enum(TicketImpact::class)],
            'impact_detail' => ['nullable', 'string', 'max: 5000'],
            'type' => ['required', Rule::enum(TicketType::class)]
        ];
    }
}
