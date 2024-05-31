<?php

namespace App\Http\Controllers\Api;

use App\Enums\ContractStatus;
use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Http\Resources\Collections\GenericCollection;
use App\Http\Resources\ContractResource;
use App\Models\Contract;
use App\Http\Requests\StoreContractRequest;
use App\Http\Requests\UpdateContractRequest;
use App\Repositories\Contract\IContractRepository;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class ContractController extends Controller
{
    protected $contractRepository;

    public function __construct(IContractRepository $contractRepository)
    {
        $this->contractRepository = $contractRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        Gate::authorize('viewAny', Contract::class);
        if (Auth::user()->role === UserRole::Manager) {
            $contracts = $this->contractRepository->paginate();
        } else {
            $contracts = $this->contractRepository->paginateByUser(Auth::user()->id);
        }
        return $this->sendResponse("Get Contract List", 200, new GenericCollection($contracts, ContractResource::class));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreContractRequest $request)
    {
        try {
            Gate::authorize('create', Contract::class);
            $data = $request->validated();
            $data['status'] = ContractStatus::Pending;
            $result = $this->contractRepository->create($data);
            return $this->sendResponse("Contract Created", 200, new ContractResource($result));
        } catch (AuthorizationException $e) {
            return $this->sendUnauthorized("You do not have permission to do this action");
        } catch (Exception $ex) {
            return $this->sendInternalError("Error", $ex->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Contract $contract)
    {
        try {
            Gate::authorize('view', $contract);
            $result = $this->contractRepository->find($contract->id);
            return $this->sendResponse("Get Contract Detail", 200, new ContractResource($result));
        } catch (ModelNotFoundException $ex) {
            return $this->sendNotFound("Contract is not exist or already deleted");
        } catch (Exception $ex) {
            return $this->sendInternalError("Error", $ex->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateContractRequest $request, Contract $contract)
    {
        try {
            Gate::authorize('update', $contract);
            $data = $request->validated();
            if ($data['status'] !== ContractStatus::Pending) {
                return $this->sendBadRequest('Contract cannot be updated after pending term');
            }
            $data['status'] = $this->contractRepository->setStatus($data['start_date'], $data['duration']);
            $result = $this->contractRepository->update($contract->id, $data);
            return $this->sendResponse("Contract Updated", 200, new ContractResource($result));
        } catch (AuthorizationException $e) {
            return $this->sendUnauthorized("You do not have permission to do this action");
        } catch (ModelNotFoundException $ex) {
            return $this->sendNotFound("Contract is not exist or already deleted");
        } catch (Exception $ex) {
            return $this->sendInternalError("Error", $ex->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Contract $contract)
    {
        try {
            Gate::authorize('delete', $contract);
            $this->contractRepository->delete($contract->id);
            return $this->sendResponse("Contract Deleted", 200);
        } catch (AuthorizationException $e) {
            return $this->sendUnauthorized("You do not have permission to do this action");
        } catch (ModelNotFoundException $ex) {
            return $this->sendNotFound("Contract is not exist or already deleted");
        } catch (Exception $ex) {
            return $this->sendInternalError("Error", $ex->getMessage());
        }
    }
}
