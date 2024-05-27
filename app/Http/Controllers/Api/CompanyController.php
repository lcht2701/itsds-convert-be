<?php

namespace App\Http\Controllers\Api;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Http\Resources\Collections\GenericCollection;
use App\Http\Resources\CompanyResource;
use App\Models\Company;
use App\Http\Requests\StoreCompanyRequest;
use App\Http\Requests\UpdateCompanyRequest;
use App\Repositories\Company\ICompanyRepository;
use App\Repositories\File\IFileRepository;
use Illuminate\Support\Facades\Gate;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CompanyController extends Controller
{
    protected $companyRepository, $fileRepository;

    public function __construct(ICompanyRepository $companyRepository, IFileRepository $fileRepository)
    {
        $this->companyRepository = $companyRepository;
        $this->fileRepository = $fileRepository;
    }

    /**
     *  Get all data of the resource
     */
    public function getSelectList()
    {
        Gate::authorize('viewAny', Company::class);
        $categories = $this->companyRepository->getSelectList();
        return $this->sendResponse("Get All Company", 200, CompanyResource::collection($categories));
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        Gate::authorize('viewAny', Company::class);
        if (Auth::user()->role === UserRole::Manager) {
            $categories = $this->companyRepository->paginate();
        } else {
            $categories = $this->companyRepository->paginateByUser(Auth::user()->role);
        }
        return $this->sendResponse("Get Company List", 200, new GenericCollection($categories, CompanyResource::class));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCompanyRequest $request)
    {
        try {
            Gate::authorize('create', Company::class);
            $data = $request->validated();

            //Upload file to local storage
            if (!empty($data['logo'])) {
                $path = 'company/' . Str::random(10);
                $logo = $data['logo'];
                $data['logo_url'] = $this->fileRepository->uploadFile($logo, $path);
            }

            $result = $this->companyRepository->create($data);
            return $this->sendResponse("Company Created", 200, new CompanyResource($result));
        } catch (AuthorizationException $e) {
            return $this->sendUnauthorized("You do not have permission to do this action");
        } catch (Exception $ex) {
            return $this->sendInternalError("Error", $ex);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Company $company)
    {
        try {
            Gate::authorize('view', $company);
            $result = $this->companyRepository->find($company->id);
            return $this->sendResponse("Get Company Detail", 200, new CompanyResource($result));
        } catch (ModelNotFoundException $ex) {
            return $this->sendNotFound("Company is not exist or already deleted");
        } catch (Exception $ex) {
            return $this->sendInternalError("Error", $ex);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCompanyRequest $request, Company $company)
    {
        try {
            Gate::authorize('update', $company);
            $data = $request->validated();

            //Update Image
            if (!empty($data['logo'])) {
                $path = 'company/' . Str::random(10);
                $logo = $data['logo'];
                $data['logo_url'] = $this->fileRepository->uploadFile($logo, $path);

                if ($company['logo_url']) {
                    $this->fileRepository->deleteFile($company['logo_url']);
                }
            }
            unset($data['logo']);
            $result = $this->companyRepository->update($company->id, $data);
            return $this->sendResponse("Company Updated", 200, new CompanyResource($result));
        } catch (AuthorizationException $e) {
            return $this->sendUnauthorized("You do not have permission to do this action");
        } catch (ModelNotFoundException $ex) {
            return $this->sendNotFound("Company is not exist or already deleted");
        } catch (Exception $ex) {
            return $this->sendInternalError("Error", $ex);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Company $company)
    {
        try {
            Gate::authorize('delete', $company);
            $this->companyRepository->delete($company->id);
            return $this->sendResponse("Company Deleted", 200);
        } catch (AuthorizationException $e) {
            return $this->sendUnauthorized("You do not have permission to do this action");
        } catch (ModelNotFoundException $ex) {
            return $this->sendNotFound("Company is not exist or already deleted");
        } catch (Exception $ex) {
            return $this->sendInternalError("Error", $ex);
        }
    }
}
