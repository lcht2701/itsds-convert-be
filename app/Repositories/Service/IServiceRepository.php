<?php

namespace App\Repositories\Service;

interface IServiceRepository
{
    public function all($columns = ['*'], $orderBy = 'created_at', $sortBy = 'desc');

    public function paginate($perPage = 10, $columns = ['*'], $orderBy = 'created_at', $sortBy = 'desc');

    public function create(array $data);

    public function update($id, array $data);

    public function delete($id);

    public function find($id);
}
