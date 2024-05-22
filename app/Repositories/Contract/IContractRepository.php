<?php

namespace App\Repositories\Contract;

use App\Models\Contract;

interface IContractRepository
{
    public function all($columns = ['*'], $orderBy = 'created_at', $sortBy = 'desc');

    public function paginate($perPage = 10, $columns = ['*'], $orderBy = 'created_at', $sortBy = 'desc');

    public function create(array $data);

    public function update($id, array $data);

    public function delete($id);

    public function find($id);

    public function setStatus($startDate, $duration);
}
