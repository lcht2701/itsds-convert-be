<?php

namespace App\Repositories\TicketTask;

use App\Enums\TaskStatus;
use App\Models\TicketTask;

class TicketTaskRepository implements ITicketTaskRepository
{
    public function paginate($ticketId, $perPage = 10, $columns = ['*'], $orderBy = 'created_at', $sortBy = 'desc')
    {
        return TicketTask::where('ticket_id', $ticketId)
            ->orderBy($orderBy, $sortBy)
            ->paginate($perPage, $columns);
    }

    public function create(array $data)
    {
        return TicketTask::create($data);
    }

    public function update($id, array $data)
    {
        $ticketTask = TicketTask::findOrFail($id);
        $ticketTask->update($data);
        return $ticketTask;
    }

    public function delete($id)
    {
        $ticketTask = TicketTask::findOrFail($id);
        $ticketTask->delete();
    }

    public function find($id)
    {
        return TicketTask::findOrFail($id);
    }

    public function updateStatus($id, $newStatus)
    {
        $ticketTask = TicketTask::findOrFail($id);
        $ticketTask->task_status = $newStatus;
        switch ($newStatus) {
            case TaskStatus::Closed: {
                    $ticketTask->progress = 100;
                    $ticketTask->end_time = now();
                    break;
                }
            case TaskStatus::Cancelled: {
                    $ticketTask->progress = 0;
                    $ticketTask->end_time = now();
                    break;
                }
            default: {
                    $ticketTask->end_time = null;
                    break;
                }
        }
        $ticketTask->save();
        return $ticketTask;
    }
}
