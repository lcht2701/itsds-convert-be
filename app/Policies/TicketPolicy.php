<?php

namespace App\Policies;

use App\Enums\TicketStatus;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TicketPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->isCustomer() || $user->isCompanyAdmin() || $user->isManager();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Ticket $ticket): bool
    {
        return $user->isCustomer() || $user->isCompanyAdmin() || $user->isManager();
    }

    /**
     * Determine whether the user can create models.
     */
    public function createByCustomer(User $user): bool
    {
        return $user->isCustomer() || $user->isCompanyAdmin();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function updateByCustomer(User $user, Ticket $ticket): bool
    {
        return
            $ticket->ticketStatus === TicketStatus::Assigned &&
            ($user->isCustomer() || $user->isCompanyAdmin());
    }

    /**
     * Determine whether the user can create models.
     */
    public function createByManager(User $user): bool
    {
        return $user->isManager() || $user->isTechnician();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Ticket $ticket): bool
    {
        return ($user->isManager() || $user->isTechnician()) &&
            ($ticket->ticketStatus !== TicketStatus::Closed &&
                $ticket->ticketStatus !== TicketStatus::Cancelled);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Ticket $ticket): bool
    {
        return $user->isManager();
    }

    /**
     * Determine whether the user can update ticket status.
     */
    public function updateStatus(User $user): bool
    {
        return $user->isManager() || $user->isTechnician();
    }

    public function cancelTicket(User $user, Ticket $ticket): bool
    {
        return
            $ticket->ticketStatus === TicketStatus::Assigned &&
            ($user->isCustomer() || $user->isCompanyAdmin());
    }
}
