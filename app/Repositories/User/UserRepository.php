<?php

namespace App\Repositories\User;

use App\Models\User;

class UserRepository implements IUserRepository
{
    public function all($columns = ['*'], $orderBy = 'id', $sortBy = 'asc')
    {
        return User::orderBy($orderBy, $sortBy)->get($columns);
    }

    public function paginate($perPage = 15, $columns = ['*'], $orderBy = 'id', $sortBy = 'asc')
    {
        return User::orderBy($orderBy, $sortBy)->paginate($perPage, $columns);
    }

    public function create(array $data)
    {
        return User::create($data);
    }

    public function update($id, array $data)
    {
        $user = User::findOrFail($id);
        $user->update($data);
        return $user;
    }

    public function delete($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
    }

    public function find($id)
    {
        return User::findOrFail($id);
    }
}
