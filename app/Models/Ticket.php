<?php

namespace App\Models;

use App\Enums\Priority;
use App\Enums\TicketImpact;
use App\Enums\TicketStatus;
use App\Enums\TicketType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ticket extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'requester_id', 'title', 'description',
        'service_id', 'ticketStatus', 'priority',
        'completed_time', 'impact', 'impact_detail',
        'type'
    ];

    protected function casts(): array
    {
        return [
            'ticketStatus' => TicketStatus::class,
            'type' => TicketType::class,
            'priority' => Priority::class,
            'impact' => TicketImpact::class
        ];
    }

    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id');
    }

    public function requester()
    {
        return $this->belongsTo(User::class, 'requester_id');
    }
}
