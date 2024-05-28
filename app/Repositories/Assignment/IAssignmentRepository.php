<?php

namespace App\Repositories\Assignment;

interface IAssignmentRepository
{
    public function getTechnicians($columns = ['*'], $orderBy = 'name', $sortBy = 'asc');

    public function create(array $data);

    public function delete($id);

    public function findByTicket($ticketId);
}
