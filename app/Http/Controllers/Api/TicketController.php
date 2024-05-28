<?php

namespace App\Http\Controllers\Api;

use App\Enums\TicketStatus;
use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTicketCustomerRequest;
use App\Http\Requests\UpdateTicketCustomerRequest;
use App\Http\Requests\StoreTicketRequest;
use App\Http\Requests\UpdateTicketRequest;
use App\Http\Resources\Collections\GenericCollection;
use App\Http\Resources\TicketResource;
use App\Models\Ticket;
use App\Repositories\Ticket\ITicketRepository;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class TicketController extends Controller
{
    protected $ticketRepository;

    public function __construct(ITicketRepository $ticketRepository)
    {
        $this->ticketRepository = $ticketRepository;
    }

    /**
     * Display a listing of the ticket.
     */
    public function index()
    {
        try {
            $user = Auth::user();
            Gate::authorize('viewAny', $user);
            switch ($user->role) {
                case UserRole::Customer:
                case UserRole::CompanyAdmin:
                    $tickets = $this->ticketRepository->paginateByUser($user->id);
                    break;
                case UserRole::Technician:
                    $tickets = $this->ticketRepository->paginateByTechnician($user->id);
                    break;
                case UserRole::Manager:
                    $tickets = $this->ticketRepository->paginate();
                    break;
                default:
                    $tickets = collect();
                    break;
            }
            return $this->sendResponse("Get Ticket List", 200, new GenericCollection($tickets, TicketResource::class));
        } catch (AuthorizationException) {
            return $this->sendUnauthorized("You do not have permission to do this action");
        } catch (Exception $ex) {
            return $this->sendInternalError("Error", $ex->getMessage());
        }
    }

    /**
     * Display the specified ticket.
     */
    public function show(Ticket $ticket)
    {
        try {
            Gate::authorize('view', $ticket);
            $result = $this->ticketRepository->find($ticket->id);
            return $this->sendResponse("Get Company Detail", 200, new TicketResource($result));
        } catch (ModelNotFoundException $ex) {
            return $this->sendNotFound("Company is not exist or already deleted");
        } catch (Exception $ex) {
            return $this->sendInternalError("Error", $ex->getMessage());
        }
    }

    /**
     * Store a ticket created by customer
     */
    public function storeByCustomer(StoreTicketCustomerRequest $request)
    {
        try {
            Gate::authorize('createByCustomer', Ticket::class);
            $data = $request->validated();
            $data['requester_id'] = Auth::user()->id;
            //Create a job to auto assign
            $data['ticketStatus'] = TicketStatus::Assigned;
            $result = $this->ticketRepository->create($data);
            return $this->sendResponse("Ticket Created", 200, new TicketResource($result));
        } catch (AuthorizationException) {
            return $this->sendUnauthorized("You do not have permission to do this action");
        } catch (Exception $ex) {
            return $this->sendInternalError("Error", $ex);
        }
    }

    /**
     * Update the ticket by customer
     */
    public function updateByCustomer(UpdateTicketCustomerRequest $request, Ticket $ticket)
    {
        try {
            Gate::authorize('updateByCustomer', $ticket);
            $data = $request->validated();
            $result = $this->ticketRepository->update($ticket->id, $data);
            return $this->sendResponse("Ticket Updated", 200, new TicketResource($result));
        } catch (AuthorizationException) {
            return $this->sendUnauthorized("You do not have permission to do this action");
        } catch (ModelNotFoundException $ex) {
            return $this->sendNotFound("Ticket is not exist or already deleted");
        } catch (Exception $ex) {
            return $this->sendInternalError("Error", $ex->getMessage());
        }
    }

    /**
     * Remove the specified ticket from storage.
     */
    public function destroy(Ticket $ticket)
    {
        try {
            Gate::authorize('delete', $ticket);
            $this->ticketRepository->delete($ticket->id);
            return $this->sendResponse("Ticket Deleted", 200);
        } catch (AuthorizationException) {
            return $this->sendUnauthorized("You do not have permission to do this action");
        } catch (ModelNotFoundException) {
            return $this->sendNotFound("Ticket is not exist or already deleted");
        } catch (Exception $ex) {
            return $this->sendInternalError("Error", $ex->getMessage());
        }
    }

    /**
     * Store a ticket created by manager or technician
     */
    public function store(StoreTicketRequest $request)
    {
        try {
            Gate::authorize('create', Ticket::class);
            $data = $request->validated();
            //Create a job to auto assign
            $data['ticketStatus'] = TicketStatus::Assigned;
            $result = $this->ticketRepository->create($data);
            return $this->sendResponse("Ticket Created", 200, new TicketResource($result));
        } catch (AuthorizationException) {
            return $this->sendUnauthorized("You do not have permission to do this action");
        } catch (Exception $ex) {
            return $this->sendInternalError("Error", $ex);
        }
    }

    /**
     * Update the ticket by manager
     */
    public function update(UpdateTicketRequest $request, Ticket $ticket)
    {
        try {
            Gate::authorize('update', $ticket);
            $data = $request->validated();
            $result = $this->ticketRepository->update($ticket->id, $data);
            return $this->sendResponse("Ticket Updated", 200, new TicketResource($result));
        } catch (AuthorizationException) {
            return $this->sendUnauthorized("You do not have permission to do this action");
        } catch (ModelNotFoundException $ex) {
            return $this->sendNotFound("Ticket is not exist or already deleted");
        } catch (Exception $ex) {
            return $this->sendInternalError("Error", $ex->getMessage());
        }
    }

    /**
     * Update the ticket status
     */
    public function updateStatus(Ticket $ticket)
    {
        try {
            Gate::authorize('updateStatus', $ticket);
            $result = $this->ticketRepository->updateStatus($ticket->id);
            return $this->sendResponse("Ticket Updated", 200, new TicketResource($result));
        } catch (AuthorizationException) {
            return $this->sendUnauthorized("You do not have permission to do this action");
        } catch (BadRequestHttpException $ex) {
            return $this->sendBadRequest($ex->getMessage());
        } catch (ModelNotFoundException $ex) {
            return $this->sendNotFound("Ticket is not exist or already deleted");
        } catch (Exception $ex) {
            return $this->sendInternalError("Error", $ex->getMessage());
        }
    }
    /**
     * Cancel ticket
     */
    public function cancelTicket(Ticket $ticket)
    {
        try {
            Gate::authorize('cancelTicket', $ticket);
            $result = $this->ticketRepository->cancelTicket($ticket->id);
            return $this->sendResponse("Ticket Updated", 200, new TicketResource($result));
        } catch (AuthorizationException) {
            return $this->sendUnauthorized("You do not have permission to do this action or ticket is already proceeded");
        } catch (ModelNotFoundException $ex) {
            return $this->sendNotFound("Ticket is not exist or already deleted");
        } catch (Exception $ex) {
            return $this->sendInternalError("Error", $ex->getMessage());
        }
    }
}
