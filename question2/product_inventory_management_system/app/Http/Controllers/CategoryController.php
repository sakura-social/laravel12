<?php

namespace App\Http\Controllers;

use App\Http\Requests\RequestCategory;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $getCategories = Category::select('id', 'name', 'parent_id')->get();
        return $this->successResponse($getCategories, 'Categories retrieved successfully');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $validatedData = $request->validate((new Category)->rules());
        if ($validatedData instanceof \Illuminate\Http\JsonResponse) {
            return $this->errorResponse($validatedData->getData(), 422);
        }
        $category = Category::query()->create($validatedData);
        
        if (!$category) {
            return $this->errorResponse('Failed to create category', 500);
        }

        return $this->successResponse($category, 'Category created successfully', 201);
        
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        //
        if (!$category) {
            return $this->errorResponse('Category not found', 404);
        } else {
            return $this->successResponse($category, 'Category retrieved successfully');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(RequestCategory $request, Category $category)
    {
        //
        $validatedData = $request->validate($category->rules());
        if ($validatedData instanceof \Illuminate\Http\JsonResponse) {
            return $this->errorResponse($validatedData->getData(), 422);
        }

        if ($category->update($validatedData)) {
            return $this->successResponse($category, 'Category updated successfully');
        } else {
            return $this->errorResponse('Failed to update category', 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        //
        if (!$category) {
            return $this->errorResponse('Category not found', 404);
        }

        if (!$category->delete()) {
            return $this->errorResponse('Failed to delete category', 500);
        }

        return $this->successResponse($category, 'Category deleted successfully');
    }
}
