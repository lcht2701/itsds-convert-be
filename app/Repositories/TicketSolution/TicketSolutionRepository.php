<?php

namespace App\Repositories\TicketSolution;

use App\Models\TicketSolution;
use GPBMetadata\Google\Type\Datetime;

class TicketSolutionRepository implements ITicketSolutionRepository
{
    public function all($columns = ['*'], $orderBy = 'created_at', $sortBy = 'desc')
    {
        return TicketSolution::orderBy($orderBy, $sortBy)->get($columns);
    }

    public function paginate($perPage = 10, $columns = ['*'], $orderBy = 'created_at', $sortBy = 'desc')
    {
        return TicketSolution::orderBy($orderBy, $sortBy)->paginate($perPage, $columns);
    }

    public function create(array $data)
    {
        return TicketSolution::create($data);
    }

    public function update($id, array $data)
    {
        $entity = TicketSolution::findOrFail($id);
        $entity->update($data);
        return $entity;
    }

    public function delete($id)
    {
        $entity = TicketSolution::findOrFail($id);
        $entity->delete();
    }

    public function find($id)
    {
        return TicketSolution::findOrFail($id);
    }

    public function approve($id)
    {
        $entity = TicketSolution::findOrFail($id);
        $entity->review_date = now();
        $entity->update();
        return $entity;
    }

    public function reject($id)
    {
        $entity = TicketSolution::findOrFail($id);
        $entity->review_date = null;
        $entity->update();
        return $entity;
    }
}
