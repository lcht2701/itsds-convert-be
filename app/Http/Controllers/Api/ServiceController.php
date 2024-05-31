<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Collections\GenericCollection;
use App\Http\Resources\ServiceResource;
use App\Models\Service;
use App\Http\Requests\StoreServiceRequest;
use App\Http\Requests\UpdateServiceRequest;
use App\Repositories\Service\IServiceRepository;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Gate;

class ServiceController extends Controller
{
    protected $serviceRepository;

    public function __construct(IServiceRepository $serviceRepository)
    {
        $this->serviceRepository = $serviceRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function getSelectList()
    {
        $services = $this->serviceRepository->all();
        return $this->sendResponse("Get Service List", 200, ServiceResource::collection($services));
    }


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $services = $this->serviceRepository->paginate();
        return $this->sendResponse("Get Service List", 200, new GenericCollection($services, ServiceResource::class));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreServiceRequest $request)
    {
        try {
            Gate::authorize('create', Service::class);
            $data = $request->validated();
            $result = $this->serviceRepository->create($data);
            return $this->sendResponse("Service Created", 200, new ServiceResource($result));
        } catch (AuthorizationException $e) {
            return $this->sendUnauthorized("You do not have permission to do this action");
        } catch (ModelNotFoundException $ex) {
            return $this->sendNotFound("Service is not exist or already deleted");
        } catch (Exception $ex) {
            return $this->sendInternalError("Error", $ex->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Service $service)
    {
        try {
            $result = $this->serviceRepository->find($service->id);
            return $this->sendResponse("Get Service Detail", 200, new ServiceResource($result));
        } catch (ModelNotFoundException $ex) {
            return $this->sendNotFound("service is not exist or already deleted");
        } catch (Exception $ex) {
            return $this->sendInternalError("Error", $ex->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateServiceRequest $request, Service $service)
    {
        try {
            Gate::authorize('update', $service);
            $data = $request->validated();
            $result = $this->serviceRepository->update($service->id, $data);
            return $this->sendResponse("Service Updated", 200, new ServiceResource($result));
        } catch (AuthorizationException $e) {
            return $this->sendUnauthorized("You do not have permission to do this action");
        } catch (ModelNotFoundException $ex) {
            return $this->sendNotFound("Service is not exist or already deleted");
        } catch (Exception $ex) {
            return $this->sendInternalError("Error", $ex->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Service $service)
    {
        try {
            Gate::authorize('delete', $service);
            $this->serviceRepository->delete($service->id);
            return $this->sendResponse("Service Deleted", 200);
        } catch (AuthorizationException $e) {
            return $this->sendUnauthorized("You do not have permission to do this action");
        } catch (ModelNotFoundException $ex) {
            return $this->sendNotFound("Service is not exist or already deleted");
        } catch (Exception $ex) {
            return $this->sendInternalError("Error", $ex->getMessage());
        }
    }
}
