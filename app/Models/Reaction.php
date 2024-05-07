<?php

namespace App\Models;

use App\Enums\ReactionType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Reaction extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'ticket_solution_id',
        'user_id',
        'reaction_type'
    ];

    protected function casts(): array
    {
        return [
            'reaction_type' => ReactionType::class,
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function ticketSolution()
    {
        return $this->belongsTo(TicketSolution::class, 'ticket_solution_id');
    }
}
