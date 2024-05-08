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
    public function index(CompanyMember $companyMember)
    {
        $members = $this->companyMemberRepository->paginateByCompany($companyMember->id);
        return $this->sendResponse("Get Company Member List", 200, new GenericCollection($members, CompanyMemberResource::class));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Company $company, StoreCompanyMemberRequest $request)
    {

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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CompanyMember $companyMember)
    {
        try {
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
