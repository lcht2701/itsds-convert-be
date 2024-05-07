<?php

namespace App\Repositories\TicketSolution;

interface ITicketSolutionRepository
{
    public function all($columns = ['*'], $orderBy = 'created_at', $sortBy = 'desc');

    public function paginate($perPage = 15, $columns = ['*'], $orderBy = 'created_at', $sortBy = 'desc');

    public function create(array $data);

    public function update($id, array $data);

    public function delete($id);

    public function find($id);
}
