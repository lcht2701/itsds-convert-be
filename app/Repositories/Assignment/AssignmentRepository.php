<?php

namespace App\Repositories\Assignment;

use App\Enums\UserRole;
use App\Models\Assignment;
use App\Models\User;

class AssignmentRepository implements IAssignmentRepository
{
    public function getTechnicians($columns = ['*'], $orderBy = 'name', $sortBy = 'asc')
    {
        return User::where('role', UserRole::Technician)
            ->orderBy($orderBy, $sortBy)
            ->get($columns);
    }

    public function create(array $data)
    {
        return Assignment::create($data);
    }

    public function delete($id)
    {
        $assignment = Assignment::findOrFail($id);
        $assignment->forceDelete();
    }

    public function findByTicket($ticketId)
    {
        return Assignment::where('ticket_id', $ticketId)->firstOrFail();
    }
}
