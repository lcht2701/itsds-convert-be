<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\Collections\GenericCollection;
use App\Models\Category;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Repositories\Category\ICategoryRepository;
use Exception;
use Illuminate\Support\Facades\Gate;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CategoryController extends Controller
{
    protected $categoryRepository;

    public function __construct(ICategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function getSelectList()
    {
        $categories = $this->categoryRepository->all();
        return $this->sendResponse("Get Category Select List", 200, CategoryResource::collection($categories));
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = $this->categoryRepository->paginate();
        return $this->sendResponse("Get Category List", 200, new GenericCollection($categories, CategoryResource::class));
        // return $this->sendResponse("Get Category List", 200, $categories);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request)
    {
        try {
            Gate::authorize('create', Category::class);
            $data = $request->validated();
            $result = $this->categoryRepository->create($data);
            return $this->sendResponse("Category Created", 200, new CategoryResource($result));
        } catch (AuthorizationException $e) {
            return $this->sendUnauthorized("You do not have permission to do this action");
        } catch (Exception $ex) {
            return $this->sendInternalError("Error", $ex->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        try {
            $result = $this->categoryRepository->find($category->id);
            return $this->sendResponse("Get Category Detail", 200, new CategoryResource($result));
        } catch (ModelNotFoundException $ex) {
            return $this->sendNotFound("Category is not exist or already deleted");
        } catch (Exception $ex) {
            return $this->sendInternalError("Error", $ex->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        try {
            Gate::authorize('update', $category);
            $data = $request->validated();
            $result = $this->categoryRepository->update($category->id, $data);
            return $this->sendResponse("Category Updated", 200, new CategoryResource($result));
        } catch (AuthorizationException $e) {
            return $this->sendUnauthorized("You do not have permission to do this action");
        } catch (ModelNotFoundException $ex) {
            return $this->sendNotFound("Category is not exist or already deleted");
        } catch (Exception $ex) {
            return $this->sendInternalError("Error", $ex->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        try {
            Gate::authorize('delete', $category);
            $this->categoryRepository->delete($category->id);
            return $this->sendResponse("Category Deleted", 200);
        } catch (AuthorizationException $e) {
            return $this->sendUnauthorized("You do not have permission to do this action");
        } catch (ModelNotFoundException $ex) {
            return $this->sendNotFound("Category is not exist or already deleted");
        } catch (Exception $ex) {
            return $this->sendInternalError("Error", $ex->getMessage());
        }
    }
}
