<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\Collections\GenericCollection;
use App\Models\Category;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use Exception;
use Gate;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = Category::query();
        $categories = $query
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        return $this->sendResponse("Get Category List", 200, new GenericCollection($categories, CategoryResource::class));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request)
    {
        if (Gate::denies('role.manager')) {
            return $this->sendUnauthorized('You do not have permission to do this action');
        }

        $data = $request->validated();
        $result = Category::create($data);
        return $this->sendResponse("Category Created", 200, new CategoryResource($result));
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        try {
            return $this->sendResponse("Get Category Detail", 200, new CategoryResource($category));
        } catch (NotFoundHttpException $ex) {
            return $this->sendNotFound("Category is not exist or already deleted");
        } catch (Exception $ex) {
            return $this->sendInternalError("Error", $ex);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        try {
            if (Gate::denies('role.manager')) {
                return $this->sendUnauthorized('You do not have permission to do this action');
            }

            $data = $request->validated();
            $category->update($data);
            return $this->sendResponse("Category Updated", 200, new CategoryResource($category));
        } catch (NotFoundHttpException $ex) {
            return $this->sendNotFound("Category is not exist or already deleted");
        } catch (Exception $ex) {
            return $this->sendInternalError("Error", $ex);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        try {
            if (Gate::denies('role.manager')) {
                return $this->sendUnauthorized('You do not have permission to do this action');
            }

            $category->delete();
            return $this->sendResponse("Category Deleted", 200);
        } catch (NotFoundHttpException $ex) {
            return $this->sendNotFound("Category is not exist or already deleted");
        } catch (Exception $ex) {
            return $this->sendInternalError("Error", $ex);
        }
    }
}
