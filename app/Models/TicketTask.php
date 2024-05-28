<?php

namespace App\Models;

use App\Enums\Priority;
use App\Enums\TaskStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TicketTask extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'ticket_id', 'title', 'description',
        'note', 'priority', 'start_time',
        'end_time', 'progress', 'task_status',
        'create_by_id'
    ];

    protected function casts(): array
    {
        return [
            'task_status' => TaskStatus::class,
            'priority' => Priority::class
        ];
    }

    public function ticket()
    {
        return $this->belongsTo(Ticket::class, 'ticket_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'create_by_id');
    }
}
