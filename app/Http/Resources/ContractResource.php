<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ContractResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'contract_num' => $this->contract_num,
            'name' => $this->name,
            'description' => $this->description,
            'company' => $this->company,
            'start_date' => Carbon::parse($this->start_date)->format('Y-m-d H:m:s'),
            'duration' => $this->duration,
            'end_date' => Carbon::parse($this->start_date->addMonth($this->duration))->format('Y-m-d H:m:s'),
            'value' => $this->value,
            'status' => $this->status,
            'created_at' => Carbon::parse($this->created_at)->format('Y-m-d H:m:s'),
            'updated_at' => Carbon::parse($this->updated_at)->format('Y-m-d H:m:s'),
        ];
    }
}
