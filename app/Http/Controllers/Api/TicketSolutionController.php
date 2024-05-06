<?php

namespace App\Http\Controllers\Api;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Http\Resources\Collections\GenericCollection;
use App\Http\Resources\TicketSolutionResource;
use App\Models\TicketSolution;
use App\Http\Requests\StoreTicketSolutionRequest;
use App\Http\Requests\UpdateTicketSolutionRequest;
use Auth;
use Exception;
use Gate;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class TicketSolutionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = TicketSolution::query();
        $ticketSolutions = $query
            ->orderBy('created_at', 'desc')
            ->paginate(10);

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
            $data['created_by_id'] = Auth::user()->id;
            $data['review_date'] = null;
            $result = TicketSolution::create($data);
            return $this->sendResponse("Ticket Solution Created", 200, new TicketSolutionResource($result));
        } catch (AuthorizationException $e) {
            return $this->sendUnauthorized("You do not have permission to do this action");
        } catch (Exception $ex) {
            return $this->sendInternalError("Error", $ex);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(TicketSolution $ticketSolution)
    {
        try {
            return $this->sendResponse("Get Ticket Solution Detail", 200, new TicketSolutionResource($ticketSolution));
        } catch (ModelNotFoundException $ex) {
            return $this->sendNotFound("Ticket Solution is not exist or already deleted");
        } catch (Exception $ex) {
            return $this->sendInternalError("Error", $ex);
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
            $ticketSolution->update($data);
            return $this->sendResponse("Ticket Solution Updated", 200, new TicketSolutionResource($ticketSolution));
        } catch (AuthorizationException $e) {
            return $this->sendUnauthorized("You do not have permission to do this action");
        } catch (ModelNotFoundException $ex) {
            return $this->sendNotFound("Ticket Solution is not exist or already deleted");
        } catch (Exception $ex) {
            return $this->sendInternalError("Error", $ex);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TicketSolution $ticketSolution)
    {
        try {
            Gate::authorize('delete', $ticketSolution);
            $ticketSolution->delete();
            return $this->sendResponse("Ticket Solution Deleted", 200);
        } catch (AuthorizationException $e) {
            return $this->sendUnauthorized("You do not have permission to do this action");
        } catch (ModelNotFoundException $ex) {
            return $this->sendNotFound("Ticket Solution is not exist or already deleted");
        } catch (Exception $ex) {
            return $this->sendInternalError("Error", $ex);
        }
    }

    public function approve(TicketSolution $ticketSolution)
    {
        try {
            Gate::authorize('approve', $ticketSolution);
            $ticketSolution['review_date'] = now();
            $ticketSolution->update();
            return $this->sendResponse("Ticket Solution Approved", 200, new TicketSolutionResource($ticketSolution));
        } catch (AuthorizationException $e) {
            return $this->sendUnauthorized("You do not have permission to do this action");
        } catch (ModelNotFoundException $ex) {
            return $this->sendNotFound("Ticket Solution is not exist or already deleted");
        } catch (Exception $ex) {
            return $this->sendInternalError("Error", $ex);
        }
    }
    public function reject(TicketSolution $ticketSolution)
    {
        try {
            Gate::authorize('reject', $ticketSolution);
            $ticketSolution['review_date'] = null;
            $ticketSolution->update();
            return $this->sendResponse("Ticket Solution Rejected", 200, new TicketSolutionResource($ticketSolution));
        } catch (AuthorizationException $e) {
            return $this->sendUnauthorized("You do not have permission to do this action");
        } catch (ModelNotFoundException $ex) {
            return $this->sendNotFound("Ticket Solution is not exist or already deleted");
        } catch (Exception $ex) {
            return $this->sendInternalError("Error", $ex);
        }
    }
}
