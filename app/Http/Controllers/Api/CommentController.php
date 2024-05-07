<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Collections\GenericCollection;
use App\Http\Resources\CommentResource;
use App\Models\Comment;
use App\Http\Requests\StoreCommentRequest;
use App\Http\Requests\UpdateCommentRequest;
use App\Repositories\Comment\ICommentRepository;
use Gate;

class CommentController extends Controller
{
    protected $commentRepository;
    public function __construct(ICommentRepository $commentRepository)
    {
        $this->commentRepository = $commentRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index($solutionId)
    {
        $query = Comment::query();
        $comments = $query->where('ticket_solution_id', $solutionId)->orderBy('created_at', 'desc')->paginate(10);
        // $comments = $this->commentRepository->allbySolution($solutionId);
        return $this->sendResponse("Get Comment List", 200, new GenericCollection($comments, CommentResource::class));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCommentRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Comment $comment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCommentRequest $request, Comment $comment)
    {
        Gate::authorize('update', $comment);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Comment $comment)
    {
        //
    }
}
