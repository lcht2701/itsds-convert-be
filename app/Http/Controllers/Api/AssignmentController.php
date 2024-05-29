<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Http\Requests\StoreAssignmentRequest;
use App\Http\Resources\AssignmentResource;
use App\Http\Resources\UserResource;
use App\Models\Ticket;
use App\Repositories\Assignment\IAssignmentRepository;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class AssignmentController extends Controller
{
    protected $assignmentRepository;

    public function __construct(IAssignmentRepository $assignmentRepository)
    {
        $this->assignmentRepository = $assignmentRepository;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function getTechnicians()
    {
        try {
            Gate::authorize('getTechnician', Auth::user());
            $technicians = $this->assignmentRepository->getTechnicians();
            return $this->sendResponse('Get Technician List', 200, UserResource::collection($technicians));
        } catch (AuthorizationException) {
            return $this->sendUnauthorized("You do not have permission to do this action");
        } catch (Exception $ex) {
            return $this->sendInternalError("Error", $ex->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Ticket $ticket, StoreAssignmentRequest $request)
    {
        try {
            Gate::authorize('create', Assignment::class);
            $data = $request->validated();
            //Kiem tra ticket da duoc phan cong, neu co thi tien hanh xoa de phan cong moi
            $assigment = $this->assignmentRepository->findByTicket($ticket->id);
            if ($assigment) $this->assignmentRepository->delete($assigment);
            //Tao Assignment moi
            $data['ticket_id'] = $ticket->id;
            $result = $this->assignmentRepository->create($data);
            return $this->sendResponse("Ticket Assigned", 200, new AssignmentResource($result));
        } catch (AuthorizationException) {
            return $this->sendUnauthorized("You do not have permission to do this action");
        } catch (Exception $ex) {
            return $this->sendInternalError("Error", $ex->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Ticket $ticket, Assignment $assignment)
    {
        try {
            Gate::authorize('view', $assignment);
            $result = $this->assignmentRepository->findByTicket($ticket->id);
            return $this->sendResponse("Get Ticket Assignment", 200, new AssignmentResource($result));
        } catch (ModelNotFoundException $ex) {
            return $this->sendNotFound("Company is not exist or already deleted");
        } catch (Exception $ex) {
            return $this->sendInternalError("Error", $ex->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Assignment $assignment)
    {
        try {
            Gate::authorize('delete', $assignment);
            $this->assignmentRepository->delete($assignment->id);
            return $this->sendResponse("Ticket Assignment Deleted", 200);
        } catch (AuthorizationException) {
            return $this->sendUnauthorized("You do not have permission to do this action");
        } catch (ModelNotFoundException) {
            return $this->sendNotFound("Assignment is not exist or already deleted");
        } catch (Exception $ex) {
            return $this->sendInternalError("Error", $ex->getMessage());
        }
    }
}
