<?php

namespace App\Policies;

use App\Enums\TicketStatus;
use App\Models\Ticket;
use App\Models\TicketTask;
use App\Models\User;

class TicketTaskPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->isManager() || $user->isTechnician();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, TicketTask $ticketTask): bool
    {
        return $user->isManager() || $user->isTechnician();
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user, Ticket $ticket): bool
    {
        return $ticket->ticketStatus !== TicketStatus::Closed &&
            $ticket->ticketStatus !== TicketStatus::Cancelled &&
            ($user->isManager() || $user->isTechnician());
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Ticket $ticket): bool
    {
        return $ticket->ticketStatus !== TicketStatus::Closed &&
            $ticket->ticketStatus !== TicketStatus::Cancelled &&
            ($user->isManager() || $user->isTechnician());
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Ticket $ticket): bool
    {
        return $ticket->ticketStatus !== TicketStatus::Closed &&
            $ticket->ticketStatus !== TicketStatus::Cancelled &&
            ($user->isManager());
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function updateStatus(User $user, Ticket $ticket): bool
    {
        return $ticket->ticketStatus !== TicketStatus::Closed &&
            $ticket->ticketStatus !== TicketStatus::Cancelled &&
            ($user->isManager() || $user->isTechnician());
    }
}
