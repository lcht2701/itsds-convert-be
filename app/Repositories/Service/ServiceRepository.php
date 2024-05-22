<?php

namespace App\Repositories\Service;

use App\Models\Service;

class ServiceRepository implements IServiceRepository
{
    public function all($columns = ['*'], $orderBy = 'created_at', $sortBy = 'desc')
    {
        return Service::orderBy($orderBy, $sortBy)->get($columns);
    }

    public function paginate($perPage = 10, $columns = ['*'], $orderBy = 'created_at', $sortBy = 'desc')
    {
        return Service::orderBy($orderBy, $sortBy)->paginate($perPage, $columns);
    }

    public function create(array $data)
    {
        return Service::create($data);
    }

    public function update($id, array $data)
    {
        $user = Service::findOrFail($id);
        $user->update($data);
        return $user;
    }

    public function delete($id)
    {
        $user = Service::findOrFail($id);
        $user->delete();
    }

    public function find($id)
    {
        return Service::findOrFail($id);
    }
}
