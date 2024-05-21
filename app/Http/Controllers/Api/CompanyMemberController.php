<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Collections\GenericCollection;
use App\Http\Resources\CompanyMemberResource;
use App\Models\Company;
use App\Models\CompanyMember;
use App\Http\Requests\StoreCompanyMemberRequest;
use App\Http\Requests\UpdateCompanyMemberRequest;
use App\Http\Resources\UserResource;
use App\Repositories\CompanyMember\ICompanyMemberRepository;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Gate;

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
    public function getSelectList(Company $company)
    {
        try {
            Gate::authorize('view', CompanyMember::class);
            $members = $this->companyMemberRepository->getMembersNotInCompany($company->id);
            return $this->sendResponse("Get Select List", 200, UserResource::collection($members));
        } catch (AuthorizationException $e) {
            return $this->sendUnauthorized("You do not have permission to do this action");
        } catch (Exception $ex) {
            return $this->sendInternalError("Error", $ex);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Company $company, StoreCompanyMemberRequest $request)
    {
        try {
            Gate::authorize('create', CompanyMember::class);
            $data = $request->validated();
            $data['company_id'] = $company->id;
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
    public function show(Company $company, CompanyMember $member)
    {
        try {
            $result = $this->companyMemberRepository->find($member->id);
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
    public function update(Company $company, UpdateCompanyMemberRequest $request, CompanyMember $member)
    {
        try {
            Gate::authorize('update', $member);
            $data = $request->validated();
            $result = $this->companyMemberRepository->update($member->id, $data);
            return $this->sendResponse("Company Member Updated", 200, new CompanyMemberResource($result));
        } catch (AuthorizationException) {
            return $this->sendUnauthorized("You do not have permission to do this action");
        } catch (ModelNotFoundException) {
            return $this->sendNotFound("Company Member is not exist or already deleted");
        } catch (Exception $ex) {
            return $this->sendInternalError("Error", $ex);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Company $company, CompanyMember $member)
    {
        try {
            Gate::authorize('delete', $member);
            $this->companyMemberRepository->delete($member->id);
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
