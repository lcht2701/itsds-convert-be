<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\Collections\CategoryCollection;
use App\Http\Resources\Collections\GenericCollection;
use App\Models\Category;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use Gate;
use Illuminate\Auth\Access\AuthorizationException;

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
        return $this->sendResponse("Get Category List", 200, new GenericCollection(CategoryResource::collection($categories)));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request)
    {
        if (Gate::denies('role.manager')) {
            return $this->sendUnauthorized();
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
        return $this->sendResponse("Get Category Detail", 200, new CategoryResource($category));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        if (Gate::denies('role.manager')) {
            return $this->sendUnauthorized();
        }

        $data = $request->validated();
        $result = $category::update($data);
        return $this->sendResponse("Category Updated", 200, new CategoryResource($result));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        if (Gate::denies('role.manager')) {
            return $this->sendUnauthorized();
        }

        $category->delete();
        return $this->sendResponse("Category Deleted", 200);
    }
}
