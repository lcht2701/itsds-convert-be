<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TicketTaskResource extends JsonResource
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
            'ticket' => $this->ticket,
            'title' => $this->title,
            'description' => $this->description,
            'note' => $this->note,
            'priority' => $this->priority,
            'start_time' => Carbon::parse($this->start_time)->parse('Y-m-d H:i:s'),
            'end_time' => Carbon::parse($this->end_time)->parse('Y-m-d H:i:s'),
            'progress' => $this->progress,
            'task_status' => $this->task_status,
            'created_by' => $this->createdBy,
            'created_at' => Carbon::parse($this->created_at)->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::parse($this->updated_at)->format('Y-m-d H:i:s'),
        ];
    }
}
