<?php

namespace App\Http\Controllers\Api;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Http\Resources\Collections\GenericCollection;
use App\Http\Resources\TicketSolutionResource;
use App\Models\TicketSolution;
use App\Http\Requests\StoreTicketSolutionRequest;
use App\Http\Requests\UpdateTicketSolutionRequest;
use App\Repositories\TicketSolution\ITicketSolutionRepository;
use Illuminate\Support\Facades\Auth;
use Exception;
use Illuminate\Support\Facades\Gate;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class TicketSolutionController extends Controller
{
    protected $ticketSolutionRepository;

    public function __construct(ITicketSolutionRepository $ticketSolutionRepository)
    {
        $this->ticketSolutionRepository = $ticketSolutionRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $ticketSolutions = $this->ticketSolutionRepository->paginate();
        return $this->sendResponse('Get Ticket Solution List', 200, new GenericCollection($ticketSolutions, TicketSolutionResource::class));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTicketSolutionRequest $request)
    {
        try {
            Gate::authorize('create', TicketSolution::class);
            $data = $request->validated();
            $data['created_by_id'] = Auth::user()->id; // default userId who create ticket solution
            $data['review_date'] = null; // default date for review_date
            $result = $this->ticketSolutionRepository->create($data);
            return $this->sendResponse("Ticket Solution Created", 200, new TicketSolutionResource($result));
        } catch (AuthorizationException $e) {
            return $this->sendUnauthorized("You do not have permission to do this action");
        } catch (Exception $ex) {
            return $this->sendInternalError("Error", $ex->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(TicketSolution $ticketSolution)
    {
        try {
            $result = $this->ticketSolutionRepository->find($ticketSolution->id);
            return $this->sendResponse("Get Ticket Solution Detail", 200, new TicketSolutionResource($result));
        } catch (ModelNotFoundException $ex) {
            return $this->sendNotFound("Ticket Solution is not exist or already deleted");
        } catch (Exception $ex) {
            return $this->sendInternalError("Error", $ex->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTicketSolutionRequest $request, TicketSolution $ticketSolution)
    {
        try {
            Gate::authorize('update', $ticketSolution);
            $data = $request->validated();
            $result = $this->ticketSolutionRepository->update($ticketSolution->id, $data);
            return $this->sendResponse("Ticket Solution Updated", 200, new TicketSolutionResource($result));
        } catch (AuthorizationException $e) {
            return $this->sendUnauthorized("You do not have permission to do this action");
        } catch (ModelNotFoundException $ex) {
            return $this->sendNotFound("Ticket Solution is not exist or already deleted");
        } catch (Exception $ex) {
            return $this->sendInternalError("Error", $ex->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TicketSolution $ticketSolution)
    {
        try {
            Gate::authorize('delete', $ticketSolution);
            $this->ticketSolutionRepository->delete($ticketSolution->id);
            return $this->sendResponse("Ticket Solution Deleted", 200);
        } catch (AuthorizationException $e) {
            return $this->sendUnauthorized("You do not have permission to do this action");
        } catch (ModelNotFoundException $ex) {
            return $this->sendNotFound("Ticket Solution is not exist or already deleted");
        } catch (Exception $ex) {
            return $this->sendInternalError("Error", $ex->getMessage());
        }
    }

    public function approve(TicketSolution $ticketSolution)
    {
        try {
            Gate::authorize('approve', $ticketSolution);
            $result = $this->ticketSolutionRepository->approve($ticketSolution->id);
            return $this->sendResponse("Ticket Solution Approved", 200, new TicketSolutionResource($result));
        } catch (AuthorizationException $e) {
            return $this->sendUnauthorized("You do not have permission to do this action");
        } catch (ModelNotFoundException $ex) {
            return $this->sendNotFound("Ticket Solution is not exist or already deleted");
        } catch (Exception $ex) {
            return $this->sendInternalError("Error", $ex->getMessage());
        }
    }
    public function reject(TicketSolution $ticketSolution)
    {
        try {
            Gate::authorize('reject', $ticketSolution);
            $result = $this->ticketSolutionRepository->reject($ticketSolution->id);
            return $this->sendResponse("Ticket Solution Rejected", 200, new TicketSolutionResource($result));
        } catch (AuthorizationException $e) {
            return $this->sendUnauthorized("You do not have permission to do this action");
        } catch (ModelNotFoundException $ex) {
            return $this->sendNotFound("Ticket Solution is not exist or already deleted");
        } catch (Exception $ex) {
            return $this->sendInternalError("Error", $ex->getMessage());
        }
    }
}
