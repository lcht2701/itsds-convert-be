<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Collections\GenericCollection;
use App\Http\Resources\CompanyMemberResource;
use App\Models\Company;
use App\Models\CompanyMember;
use App\Http\Requests\StoreCompanyMemberRequest;
use App\Http\Requests\UpdateCompanyMemberRequest;
use App\Repositories\CompanyMember\ICompanyMemberRepository;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Gate;

class CompanyMemberController extends Controller
{
    protected $companyMemberRepository;

    public function __construct(ICompanyMemberRepository $companyMemberRepository)
    {
        $this->companyMemberRepository = $companyMemberRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Company $company)
    {
        try {
            Gate::authorize('viewAny', CompanyMember::class);
            $members = $this->companyMemberRepository->paginateByCompany($company->id);
            return $this->sendResponse("Get Company Member List", 200, new GenericCollection($members, CompanyMemberResource::class));
        } catch (AuthorizationException $e) {
            return $this->sendUnauthorized("You do not have permission to do this action");
        } catch (Exception $ex) {
            return $this->sendInternalError("Error", $ex);
        }
    }

    /**
     * Display a listing for selection
     */
    public function getSelectList()
    {
        try {
            Gate::authorize('view', CompanyMember::class);
            $members = $this->companyMemberRepository->getMembersNotInCompany();
            return $this->sendResponse("Get Select List", 200, CompanyMemberResource::collection($members));
        } catch (AuthorizationException $e) {
            return $this->sendUnauthorized("You do not have permission to do this action");
        } catch (Exception $ex) {
            return $this->sendInternalError("Error", $ex);
        }

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCompanyMemberRequest $request)
    {
        try {
            Gate::authorize('create', CompanyMember::class);
            $data = $request->validated();
            $result = $this->companyMemberRepository->create($data);
            return $this->sendResponse("Company Address Created", 200, new CompanyMemberResource($result));
        } catch (AuthorizationException $e) {
            return $this->sendUnauthorized("You do not have permission to do this action");
        } catch (Exception $ex) {
            return $this->sendInternalError("Error", $ex);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(CompanyMember $companyMember)
    {
        try {
            $result = $this->companyMemberRepository->find($companyMember->id);
            return $this->sendResponse("Get Company Member Detail", 200, new CompanyMemberResource($result));
        } catch (ModelNotFoundException $ex) {
            return $this->sendNotFound("Company Member is not exist or already deleted");
        } catch (Exception $ex) {
            return $this->sendInternalError("Error", $ex);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCompanyMemberRequest $request, CompanyMember $companyMember)
    {
        try {
            Gate::authorize('update', $companyMember);
            $data = $request->validated();
            $result = $this->companyMemberRepository->update($companyMember->id, $data);
            return $this->sendResponse("Company Member Updated", 200, new CompanyMemberResource($result));
        } catch (AuthorizationException $e) {
            return $this->sendUnauthorized("You do not have permission to do this action");
        } catch (ModelNotFoundException $ex) {
            return $this->sendNotFound("Company Member is not exist or already deleted");
        } catch (Exception $ex) {
            return $this->sendInternalError("Error", $ex);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CompanyMember $companyMember)
    {
        try {
            Gate::authorize('delete', $companyMember);
            $this->companyMemberRepository->delete($companyMember->id);
            return $this->sendResponse("Company Member Deleted", 200);
        } catch (AuthorizationException $e) {
            return $this->sendUnauthorized("You do not have permission to do this action");
        } catch (ModelNotFoundException $ex) {
            return $this->sendNotFound("Company Member is not exist or already deleted");
        } catch (Exception $ex) {
            return $this->sendInternalError("Error", $ex);
        }
    }
}
