<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Reaction;
use App\Http\Requests\StoreReactionRequest;
use App\Http\Requests\UpdateReactionRequest;
use App\Models\TicketSolution;
use App\Repositories\Reaction\IReactionRepository;

class ReactionController extends Controller
{
    protected $reactionRepository;

    public function __construct(IReactionRepository $reactionRepository)
    {
        $this->reactionRepository = $reactionRepository;
    }
    /**
     * Get reaction counts
     */
    public function get(TicketSolution $ticketSolution)
    {
        $result = $this->reactionRepository->get($ticketSolution);
        return $this->sendResponse('Get Ticket Solution Reaction', 200, $result);
    }

    /**
     * Like a ticket solution
     */
    public function like(TicketSolution $ticketSolution)
    {
        $result = $this->reactionRepository->like($ticketSolution);
        return $this->sendResponse('Reaction updated', 200, $result);
    }

    /**
     * Dislike a ticket solution
     */
    public function dislike(TicketSolution $ticketSolution)
    {
        $result = $this->reactionRepository->dislike($ticketSolution);
        return $this->sendResponse('Reaction updated', 200, $result);
    }
}
