<?php

namespace App\Repositories\Assignment;

use App\Models\Ticket;

interface IAssignmentRepository
{
    public function getTechnicians($columns = ['*'], $orderBy = 'name', $sortBy = 'asc');

    public function assign($ticketId);

    public function create(array $data);

    public function delete($id);

    public function findByTicket($ticketId);
}
