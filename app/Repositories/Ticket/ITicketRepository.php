<?php

namespace App\Repositories\Ticket;

interface ITicketRepository
{
    public function getSelectList($columns = ['*'], $orderBy = 'created_at', $sortBy = 'desc');

    public function all($columns = ['*'], $orderBy = 'created_at', $sortBy = 'desc');

    public function paginate($perPage = 10, $columns = ['*'], $orderBy = 'created_at', $sortBy = 'desc');

    public function paginateByUser($userId, $perPage = 10, $columns = ['*'], $orderBy = 'created_at', $sortBy = 'desc');

    public function paginateByTechnician($technicianId, $perPage = 10, $columns = ['*'], $orderBy = 'created_at', $sortBy = 'desc');

    public function create(array $data);

    public function update($id, array $data);

    public function delete($id);

    public function find($id);

    public function updateStatus($id);

    public function cancelTicket($id);
}
