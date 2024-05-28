<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TicketResource extends JsonResource
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
            'title' => $this->title,
            'description' => $this->description,
            'status' => $this->ticketStatus,
            'priority' => $this->priority,
            'impact' => $this->impact,
            'impact_detail' => $this->impact_detail,
            'type' => $this->type,
            'completed_time' => Carbon::parse($this->completed_time)->format('Y-m-d H:i:s'),
            'requester' => $this->requester,
            'service' => $this->service,
            'created_at' => Carbon::parse($this->created_at)->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::parse($this->updated_at)->format('Y-m-d H:i:s'),
        ];
    }
}
