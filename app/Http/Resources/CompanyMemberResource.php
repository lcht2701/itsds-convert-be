<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CompanyMemberResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'member' => $this->member,
            'company' => $this->company,
            'companyAddress' => $this->companyAddress,
            'created_at' => Carbon::parse($this->created_at)->format('Y-m-d H:m:s'),
            'updated_at' => Carbon::parse($this->updated_at)->format('Y-m-d H:m:s'),
        ];
    }
}
