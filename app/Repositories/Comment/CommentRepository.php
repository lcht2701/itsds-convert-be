<?php

namespace App\Repositories\Comment;

use App\Models\Comment;
use DB;

class CommentRepository implements ICommentRepository
{
    public function allBySolution($solutionId, $columns = ['*'], $orderBy = 'created_at', $sortBy = 'desc')
    {
        return Comment::where('ticket_solution_id', $solutionId)
            ->orderBy($orderBy, $sortBy)
            ->get($columns);
    }


    public function create(array $data)
    {
        return Comment::create($data);
    }

    public function update($id, array $data)
    {
        $user = Comment::findOrFail($id);
        $user->update($data);
        return $user;
    }

    public function delete($id)
    {
        $user = Comment::findOrFail($id);
        $user->delete();
    }

    public function find($id)
    {
        return Comment::findOrFail($id);
    }
}
