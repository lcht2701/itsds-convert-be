<?php

namespace App\Repositories\TicketSolution;

use App\Models\TicketSolution;

class TicketSolutionRepository implements ITicketSolutionRepository
{
    public function all($columns = ['*'], $orderBy = 'id', $sortBy = 'asc')
    {
        return TicketSolution::orderBy($orderBy, $sortBy)->get($columns);
    }

    public function paginate($perPage = 15, $columns = ['*'], $orderBy = 'id', $sortBy = 'asc')
    {
        return TicketSolution::orderBy($orderBy, $sortBy)->paginate($perPage, $columns);
    }

    public function create(array $data)
    {
        return TicketSolution::create($data);
    }

    public function update($id, array $data)
    {
        $user = TicketSolution::findOrFail($id);
        $user->update($data);
        return $user;
    }

    public function delete($id)
    {
        $user = TicketSolution::findOrFail($id);
        $user->delete();
    }

    public function find($id)
    {
        return TicketSolution::findOrFail($id);
    }
}
