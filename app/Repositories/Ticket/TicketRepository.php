<?php

namespace App\Repositories\Ticket;

use App\Enums\TicketStatus;
use App\Models\Assignment;
use App\Models\Ticket;
use App\Models\TicketTask;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class TicketRepository implements ITicketRepository
{
    public function getSelectList($columns = ['*'], $orderBy = 'created_at', $sortBy = 'desc')
    {
        return Ticket::orderBy($orderBy, $sortBy)->get($columns);
    }

    public function all($columns = ['*'], $orderBy = 'created_at', $sortBy = 'desc')
    {
        return Ticket::orderBy($orderBy, $sortBy)->get($columns);
    }

    public function paginate($perPage = 10, $columns = ['*'], $orderBy = 'created_at', $sortBy = 'desc')
    {
        return Ticket::orderBy($orderBy, $sortBy)->paginate($perPage, $columns);
    }

    public function paginateByUser($userId, $perPage = 10, $columns = ['*'], $orderBy = 'created_at', $sortBy = 'desc')
    {
        return Ticket::where('requester_id', $userId)
            ->orderBy($orderBy, $sortBy)
            ->paginate($perPage, $columns);
    }

    public function paginateByTechnician($technicianId, $perPage = 10, $columns = ['*'], $orderBy = 'created_at', $sortBy = 'desc')
    {
        return Ticket::whereIn('id', Assignment::where('technician_id', $technicianId)->pluck('ticket_id'))
            ->orderBy($orderBy, $sortBy)
            ->paginate($perPage, $columns);
    }


    public function create(array $data)
    {
        return Ticket::create($data);
    }

    public function update($id, array $data)
    {
        $ticket = Ticket::findOrFail($id);
        $ticket->update($data);
        return $ticket;
    }

    public function delete($id)
    {
        $ticket = Ticket::findOrFail($id);
        $ticket->delete();
    }

    public function find($id)
    {
        return Ticket::findOrFail($id);
    }

    public function updateStatus($id)
    {
        $ticket = Ticket::findOrFail($id);

        switch ($ticket->ticketStatus) {
            case TicketStatus::Assigned:
                $ticket->ticketStatus = TicketStatus::InProgress;
                break;
            case TicketStatus::InProgress:
                $ticket->ticketStatus = TicketStatus::Resolved;
                break;
            case TicketStatus::Resolved:
                $tasksCount = TicketTask::where('ticket_id', $ticket->id)->count();
                if ($tasksCount > 0) {
                    throw new BadRequestHttpException('All tasks must be completed before being resolved!!!');
                } else {
                    //Create Jobs to automatically close ticket
                }
                break;
        }
        $ticket->save();
        return $ticket;
    }

    public function cancelTicket($id)
    {
        $ticket = Ticket::findOrFail($id);
        $ticket['ticketStatus'] = TicketStatus::Cancelled;
        $ticket->save();
        return $ticket;
    }
}
