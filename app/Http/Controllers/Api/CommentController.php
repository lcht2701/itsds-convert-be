<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CommentResource;
use App\Models\Comment;
use App\Http\Requests\StoreCommentRequest;
use App\Http\Requests\UpdateCommentRequest;
use App\Models\TicketSolution;
use App\Repositories\Comment\ICommentRepository;
use Exception;
use Illuminate\Support\Facades\Gate;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;

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
    public function index(TicketSolution $ticketSolution)
    {
        $comments = $this->commentRepository->allbySolution($ticketSolution->id);
        return $this->sendResponse("Get Comment List", 200, CommentResource::collection($comments));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TicketSolution $ticketSolution, StoreCommentRequest $request)
    {
        try {
            $data = $request->validated();
            $data['user_id'] = Auth::user()->id;
            $data['ticket_solution_id'] = $ticketSolution->id;
            $result = $this->commentRepository->create($data);
            return $this->sendResponse("Comment Created", 200, new CommentResource($result));
        } catch (Exception $ex) {
            return $this->sendInternalError("Error", $ex);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(TicketSolution $ticketSolution, Comment $comment)
    {
        try {
            $result = $this->commentRepository->find($comment->id);
            return $this->sendResponse("Get Comment Detail", 200, new CommentResource($result));
        } catch (ModelNotFoundException $ex) {
            return $this->sendNotFound("Comment is not exist or already deleted");
        } catch (Exception $ex) {
            return $this->sendInternalError("Error", $ex);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TicketSolution $ticketSolution, UpdateCommentRequest $request, Comment $comment)
    {
        try {
            Gate::authorize('update', $comment);
            $data = $request->validated();
            $result = $this->commentRepository->update($comment->id, $data);
            return $this->sendResponse("Comment Updated", 200, new CommentResource($result));
        } catch (AuthorizationException $e) {
            return $this->sendUnauthorized("You do not have permission to do this action");
        } catch (ModelNotFoundException $ex) {
            return $this->sendNotFound("Comment is not exist or already deleted");
        } catch (Exception $ex) {
            return $this->sendInternalError("Error", $ex);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TicketSolution $ticketSolution, Comment $comment)
    {
        try {
            Gate::authorize('delete', $comment);
            $this->commentRepository->delete($comment->id);
            return $this->sendResponse("Comment Deleted", 200);
        } catch (AuthorizationException $e) {
            return $this->sendUnauthorized("You do not have permission to do this action");
        } catch (ModelNotFoundException $ex) {
            return $this->sendNotFound("Comment is not exist or already deleted");
        } catch (Exception $ex) {
            return $this->sendInternalError("Error", $ex);
        }
    }
}
