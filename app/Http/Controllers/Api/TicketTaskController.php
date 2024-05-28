<?php

namespace App\Http\Controllers\Api;

use App\Enums\TaskStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateTicketTaskStatusRequest;
use App\Models\TicketTask;
use App\Http\Requests\StoreTicketTaskRequest;
use App\Http\Requests\UpdateTicketTaskRequest;
use App\Http\Resources\Collections\GenericCollection;
use App\Http\Resources\TicketTaskResource;
use App\Models\Ticket;
use App\Repositories\TicketTask\ITicketTaskRepository;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class TicketTaskController extends Controller
{
    protected $ticketTaskRepository;

    public function __construct(ITicketTaskRepository $ticketTaskRepository)
    {
        $this->ticketTaskRepository = $ticketTaskRepository;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Ticket $ticket)
    {
        try {
            Gate::authorize('viewAny', TicketTask::class);
            $tasks = $this->ticketTaskRepository->paginate($ticket->id);
            return $this->sendResponse("Get Ticket Task List", 200, new GenericCollection($tasks, TicketTaskResource::class));
        } catch (AuthorizationException) {
            return $this->sendUnauthorized("You do not have permission to do this action");
        } catch (Exception $ex) {
            return $this->sendInternalError("Error", $ex->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Ticket $ticket, StoreTicketTaskRequest $request)
    {
        try {
            Gate::authorize('create', $ticket);
            $data = $request->validated();
            $data['create_by_id'] = Auth::user()->id;
            $data['ticket_id'] = $ticket->id;
            $result = $this->ticketTaskRepository->create($data);
            return $this->sendResponse("Ticket Task Created", 200, new TicketTaskResource($result));
        } catch (AuthorizationException) {
            return $this->sendUnauthorized("You do not have permission to do this action");
        } catch (Exception $ex) {
            return $this->sendInternalError("Error", $ex->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(TicketTask $ticketTask)
    {
        try {
            Gate::authorize('view', $ticketTask);
            $result = $this->ticketTaskRepository->find($ticketTask->id);
            return $this->sendResponse("Get Ticket Task Detail", 200, new TicketTaskResource($result));
        } catch (ModelNotFoundException $ex) {
            return $this->sendNotFound("Task is not exist or already deleted");
        } catch (Exception $ex) {
            return $this->sendInternalError("Error", $ex->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Ticket $ticket, UpdateTicketTaskRequest $request, TicketTask $ticketTask)
    {
        try {
            Gate::authorize('update', $ticket);
            $data = $request->validated();
            $result = $this->ticketTaskRepository->update($ticketTask->id, $data);
            return $this->sendResponse("Task Updated", 200, new TicketTaskResource($result));
        } catch (AuthorizationException) {
            return $this->sendUnauthorized("You do not have permission to do this action");
        } catch (ModelNotFoundException $ex) {
            return $this->sendNotFound("Task is not exist or already deleted");
        } catch (Exception $ex) {
            return $this->sendInternalError("Error", $ex->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ticket $ticket, TicketTask $ticketTask)
    {
        try {
            Gate::authorize('delete', $ticket);
            $this->ticketTaskRepository->delete($ticketTask->id);
            return $this->sendResponse("Task Deleted", 200);
        } catch (AuthorizationException) {
            return $this->sendUnauthorized("You do not have permission to do this action");
        } catch (ModelNotFoundException) {
            return $this->sendNotFound("Task is not exist or already deleted");
        } catch (Exception $ex) {
            return $this->sendInternalError("Error", $ex->getMessage());
        }
    }

    public function updateStatus(Ticket $ticket, TicketTask $ticketTask, UpdateTicketTaskStatusRequest $request)
    {
        try {
            Gate::authorize('updateStatus', $ticket);
            $data = $request->validated();
            $result = $this->ticketTaskRepository->updateStatus($ticketTask->id, $data->task_status);
            return $this->sendResponse("Task Status Updated", 200, new TicketTaskResource($result));
        } catch (AuthorizationException) {
            return $this->sendUnauthorized("You do not have permission to do this action");
        } catch (ModelNotFoundException $ex) {
            return $this->sendNotFound("Task is not exist or already deleted");
        } catch (Exception $ex) {
            return $this->sendInternalError("Error", $ex->getMessage());
        }
    }
}
