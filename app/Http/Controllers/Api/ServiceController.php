<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Collections\GenericCollection;
use App\Http\Resources\ServiceResource;
use App\Models\Service;
use App\Http\Requests\StoreServiceRequest;
use App\Http\Requests\UpdateServiceRequest;
use Exception;
use Gate;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = Service::query();

        $services = $query
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        return $this->sendResponse("Get Service List", 200, new GenericCollection($services, ServiceResource::class));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreServiceRequest $request)
    {
        try {
            if (Gate::denies('role.manager')) {
                return $this->sendUnauthorized('You do not have permission to do this action');
            }

            $data = $request->validated();
            $result = Service::create($data);
            return $this->sendResponse("Service Created", 200, new ServiceResource($result));
        } catch (Exception $ex) {
            return $this->sendInternalError("Error", $ex);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Service $service)
    {
        try {
            return $this->sendResponse("Get Service Detail", 200, new ServiceResource($service));
        } catch (NotFoundHttpException $ex) {
            return $this->sendNotFound("service is not exist or already deleted");
        } catch (Exception $ex) {
            return $this->sendInternalError("Error", $ex);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateServiceRequest $request, Service $service)
    {
        try {
            if (Gate::denies('role.manager')) {
                return $this->sendUnauthorized('You do not have permission to do this action');
            }

            $data = $request->validated();
            $service->update($data);
            return $this->sendResponse("Service Updated", 200, new ServiceResource($service));
        } catch (NotFoundHttpException $ex) {
            return $this->sendNotFound("Service is not exist or already deleted");
        } catch (Exception $ex) {
            return $this->sendInternalError("Error", $ex);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Service $service)
    {
        try {
            if (Gate::denies('role.manager')) {
                return $this->sendUnauthorized('You do not have permission to do this action');
            }

            $service->delete();
            return $this->sendResponse("Service Deleted", 200);
        } catch (NotFoundHttpException $ex) {
            return $this->sendNotFound("Service is not exist or already deleted");
        } catch (Exception $ex) {
            return $this->sendInternalError("Error", $ex);
        }
    }
}
