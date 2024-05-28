<?php

namespace App\Repositories\TicketTask;

interface ITicketTaskRepository
{
    public function paginate($ticketId, $perPage = 10, $columns = ['*'], $orderBy = 'created_at', $sortBy = 'desc');

    public function create(array $data);

    public function update($id, array $data);

    public function delete($id);

    public function find($id);

    public function updateStatus($id, $newStatus);
}
