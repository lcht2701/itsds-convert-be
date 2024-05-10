<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Collections\GenericCollection;
use App\Models\Contract;
use App\Models\ServicesContract;
use App\Http\Requests\StoreServicesContractRequest;
use App\Http\Resources\ServiceResource;
use App\Http\Resources\ServicesContractResource;
use App\Repositories\ServicesContract\IServicesContractRepository;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Gate;

class ServicesContractController extends Controller
{
    protected $servicesContractRepository;

    public function __construct(IServicesContractRepository $servicesContractRepository)
    {
        $this->servicesContractRepository = $servicesContractRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Contract $contract)
    {
        Gate::authorize('viewAny', ServicesContract::class);
        $services = $this->servicesContractRepository->paginateByContract($contract->id);
        return $this->sendResponse(
            "Get Service List From Contract",
            200,
            new GenericCollection($services, ServicesContractResource::class));
    }

    /**
     * Display a listing of the available servicesContracts to select in a ticket.
     */
    public function getAvailableServices(Contract $contract)
    {
        $services = $this->servicesContractRepository->getAvailableServices($contract->id);
        return $this->sendResponse(
            "Get Available Services List",
            200,
            ServiceResource::collection($services));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreServicesContractRequest $request)
    {
        try {
            Gate::authorize('create', ServicesContract::class);
            $data = $request->validated();
            $result = $this->servicesContractRepository->addAndUpdate($data);
            return $this->sendResponse("Service Created", 200, new ServiceResource($result));
        } catch (AuthorizationException $e) {
            return $this->sendUnauthorized("You do not have permission to do this action");
        } catch (ModelNotFoundException $ex) {
            return $this->sendNotFound("Service is not exist or already deleted");
        } catch (Exception $ex) {
            return $this->sendInternalError("Error", $ex);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(ServicesContract $servicesContractsContract)
    {
        try {
            Gate::authorize('view', ServicesContract::class);
            $result = $this->servicesContractRepository->find($servicesContractsContract->id);
            return $this->sendResponse("Get Service Detail", 200, new ServiceResource($result));
        } catch (ModelNotFoundException $ex) {
            return $this->sendNotFound("Service is not exist or already deleted");
        } catch (Exception $ex) {
            return $this->sendInternalError("Error", $ex);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ServicesContract $servicesContractsContract)
    {
        try {
            Gate::authorize('delete', $servicesContractsContract);
            $this->servicesContractRepository->delete($servicesContractsContract->id);
            return $this->sendResponse("Service Deleted", 200);
        } catch (AuthorizationException $e) {
            return $this->sendUnauthorized("You do not have permission to do this action");
        } catch (ModelNotFoundException $ex) {
            return $this->sendNotFound("Service is not exist or already deleted");
        } catch (Exception $ex) {
            return $this->sendInternalError("Error", $ex);
        }
    }
}
