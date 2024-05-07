<?php

namespace App\Repositories\Reaction;

use App\Enums\ReactionType;
use App\Models\Reaction;
use App\Models\TicketSolution;

class ReactionRepository implements IReactionRepository
{
    public function get(TicketSolution $ticketSolution)
    {
        $countLike = Reaction::where([
            'ticket_solution_id' => $ticketSolution->id,
            'reaction_type' => ReactionType::Like
        ])->count();

        $countDislike = Reaction::where([
            'ticket_solution_id' => $ticketSolution->id,
            'reaction_type' => ReactionType::Dislike
        ])->count();

        $myReaction = Reaction::firstWhere([
            'ticket_solution_id' => $ticketSolution->id,
            'user_id' => auth()->user()->id
        ]);

        $result = [
            'count_like' => $countLike,
            'count_dislike' => $countDislike,
            'my_reaction' => $myReaction->reaction_type ?? null
        ];

        return $result;
    }

    public function like(TicketSolution $ticketSolution)
    {
        return $this->toggleReaction($ticketSolution, ReactionType::Like);
    }

    public function dislike(TicketSolution $ticketSolution)
    {
        return $this->toggleReaction($ticketSolution, ReactionType::Dislike);
    }

    private function toggleReaction(TicketSolution $ticketSolution, $reactionType)
    {
        $my_reaction = Reaction::firstWhere([
            'ticket_solution_id' => $ticketSolution->id,
            'user_id' => auth()->user()->id
        ]);

        if ($my_reaction === null) {
            Reaction::create([
                'ticket_solution_id' => $ticketSolution->id,
                'user_id' => auth()->user()->id,
                'reaction_type' => $reactionType
            ]);
        } else if ($my_reaction->reaction_type === $reactionType) {
            $my_reaction->delete();
        } else {
            $my_reaction->reaction_type = $reactionType;
            $my_reaction->save();
        }

        $result = $this->get($ticketSolution);

        return $result;
    }

}
