<?php

namespace App\Repositories\Ticket;

use App\Enums\ContractStatus;
use App\Enums\TicketStatus;
use App\Models\Assignment;
use App\Models\CompanyMember;
use App\Models\Contract;
use App\Models\Service;
use App\Models\Ticket;
use App\Models\TicketTask;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class TicketRepository implements ITicketRepository
{
    public function getAvailableServices($userId, $columns = ['*'], $orderBy = 'name', $sortBy = 'asc')
    {
        $companyId = CompanyMember::where('member_id', $userId)
            ->firstOrFail()
            ->value('company_id');

        $activeContractIds = Contract::where('company_id', $companyId)
            ->where('status', ContractStatus::Active)
            ->pluck('id');

        $services = Service::whereIn('id', function ($query) use ($activeContractIds) {
            $query
                ->select('service_id')
                ->from('services_contracts')
                ->whereIn('contract_id', $activeContractIds);
        })
            ->distinct()
            ->orderBy($orderBy, $sortBy)
            ->get($columns);

        return $services;
    }

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
        if (in_array($ticket->ticketStatus, [TicketStatus::Closed, TicketStatus::Cancelled])) {
            throw new BadRequestException('Status cannot be updated due to ticket is already cancelled or closed');
        }

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
                    $ticket->ticketStatus = TicketStatus::Closed;
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
