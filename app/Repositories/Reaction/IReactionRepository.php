<?php

namespace App\Repositories\Reaction;

use App\Models\TicketSolution;

interface IReactionRepository
{
    public function get(TicketSolution $ticketSolution);

    public function like(TicketSolution $ticketSolution);

    public function dislike(TicketSolution $ticketSolution);
}
