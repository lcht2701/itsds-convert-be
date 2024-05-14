<?php

namespace App\Repositories\Category;

use App\Models\Category;

class CategoryRepository implements ICategoryRepository
{
    public function all($columns = ['*'], $orderBy = 'created_at', $sortBy = 'desc')
    {
        return Category::orderBy($orderBy, $sortBy)->get($columns);
    }

    public function paginate($perPage = 15, $columns = ['*'], $orderBy = 'created_at', $sortBy = 'desc')
    {
        return Category::orderBy($orderBy, $sortBy)->paginate($perPage, $columns);
    }

    public function create(array $data)
    {
        return Category::create($data);
    }

    public function update($id, array $data)
    {
        $user = Category::findOrFail($id);
        $user->update($data);
        return $user;
    }

    public function delete($id)
    {
        $user = Category::findOrFail($id);
        $user->delete();
    }

    public function find($id)
    {
        return Category::findOrFail($id);
    }
}
