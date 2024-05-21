<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CompanyAddressResource;
use App\Models\Company;
use App\Models\CompanyAddress;
use App\Http\Requests\StoreCompanyAddressRequest;
use App\Http\Requests\UpdateCompanyAddressRequest;
use App\Repositories\CompanyAddress\ICompanyAddressRepository;
use Exception;
use Illuminate\Support\Facades\Gate;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CompanyAddressController extends Controller
{
    protected $companyAddressRepository;
    public function __construct(ICompanyAddressRepository $companyAddressRepository)
    {
        $this->companyAddressRepository = $companyAddressRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Company $company)
    {
        $comments = $this->companyAddressRepository->allByCompany($company->id);
        return $this->sendResponse("Get Company Address List", 200, CompanyAddressResource::collection($comments));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Company $company, StoreCompanyAddressRequest $request)
    {
        try {
            Gate::authorize('create', CompanyAddress::class);
            $data = $request->validated();
            $data['company_id'] = $company->id;
            $result = $this->companyAddressRepository->create($data);
            return $this->sendResponse("Company Address Created", 200, new CompanyAddressResource($result));
        } catch (AuthorizationException $e) {
            return $this->sendUnauthorized("You do not have permission to do this action");
        } catch (Exception $ex) {
            return $this->sendInternalError("Error", $ex);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Company $company, CompanyAddress $address)
    {
        try {
            $result = $this->companyAddressRepository->find($address->id);
            return $this->sendResponse("Get Company Address Detail", 200, new CompanyAddressResource($result));
        } catch (ModelNotFoundException $ex) {
            return $this->sendNotFound("Company Address is not exist or already deleted");
        } catch (Exception $ex) {
            return $this->sendInternalError("Error", $ex);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Company $company, UpdateCompanyAddressRequest $request, CompanyAddress $address)
    {
        try {
            Gate::authorize('update', $address);
            $data = $request->validated();
            $result = $this->companyAddressRepository->update($address->id, $data);
            return $this->sendResponse("Company Address Updated", 200, new CompanyAddressResource($result));
        } catch (AuthorizationException $e) {
            return $this->sendUnauthorized("You do not have permission to do this action");
        } catch (ModelNotFoundException $ex) {
            return $this->sendNotFound("Company Address is not exist or already deleted");
        } catch (Exception $ex) {
            return $this->sendInternalError("Error", $ex);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Company $company, CompanyAddress $address)
    {
        try {
            Gate::authorize('delete', $address);
            $this->companyAddressRepository->delete($address->id);
            return $this->sendResponse("Company Address Deleted", 200);
        } catch (AuthorizationException $e) {
            return $this->sendUnauthorized("You do not have permission to do this action");
        } catch (ModelNotFoundException $ex) {
            return $this->sendNotFound("Company Address is not exist or already deleted");
        } catch (Exception $ex) {
            return $this->sendInternalError("Error", $ex);
        }
    }
}
