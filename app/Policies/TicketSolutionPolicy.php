<?php

namespace App\Policies;

use App\Models\TicketSolution;
use App\Models\User;

class TicketSolutionPolicy
{
    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->isManager() || $user->isTechnician();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, TicketSolution $ticketSolution): bool
    {
        return $user->isManager() || $user->id === $ticketSolution->created_by_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, TicketSolution $ticketSolution): bool
    {
        return $user->isManager() || $user->id === $ticketSolution->created_by_id;
    }
    public function approve(User $user, TicketSolution $ticketSolution): bool
    {
        return $user->isManager();
    }
    public function reject(User $user, TicketSolution $ticketSolution): bool
    {
        return $user->isManager();
    }
}
