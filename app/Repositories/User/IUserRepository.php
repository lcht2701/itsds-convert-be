<?php

namespace App\Repositories\User;

interface IUserRepository
{
    public function getOwnerList($columns = ['*'], $orderBy = 'name', $sortBy = 'asc');

    public function all($columns = ['*'], $orderBy = 'created_at', $sortBy = 'desc');

    public function paginate($perPage = 10, $columns = ['*'], $orderBy = 'created_at', $sortBy = 'desc');

    public function create(array $data);

    public function update($id, array $data);

    public function delete($id);

    public function find($id);
}
