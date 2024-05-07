<?php

namespace App\Repositories\Comment;

interface ICommentRepository
{
    public function allBySolution($solutionId, $columns = ['*'], $orderBy = 'created_at', $sortBy = 'desc');

    public function create(array $data);

    public function update($id, array $data);

    public function delete($id);

    public function find($id);
}
