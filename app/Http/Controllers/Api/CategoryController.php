<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Category\StoreCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of categories.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Category::withCount('courses');

        // Filter by active status
        if ($request->has('active')) {
            $query->where('is_active', $request->boolean('active'));
        }

        // Only show active categories by default
        if (!$request->has('include_inactive')) {
            $query->active();
        }

        $categories = $query->ordered()->paginate($request->get('per_page', 15));

        return $this->successResponse(
            CategoryResource::collection($categories),
            'Categories retrieved successfully'
        );
    }

    /**
     * Store a newly created category.
     */
    public function store(StoreCategoryRequest $request): JsonResponse
    {
        try {
            $category = Category::create($request->validated());

            // Handle icon upload
            if ($request->hasFile('icon')) {
                $category->addMediaFromRequest('icon')
                    ->toMediaCollection('icon');
            }

            return $this->successResponse(
                new CategoryResource($category),
                'Category created successfully',
                201
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                'Failed to create category: ' . $e->getMessage(),
                500
            );
        }
    }

    /**
     * Display the specified category.
     */
    public function show(Category $category): JsonResponse
    {
        $category->loadCount('courses');

        return $this->successResponse(
            new CategoryResource($category),
            'Category retrieved successfully'
        );
    }

    /**
     * Update the specified category.
     */
    public function update(StoreCategoryRequest $request, Category $category): JsonResponse
    {
        try {
            $category->update($request->validated());

            // Handle icon upload
            if ($request->hasFile('icon')) {
                $category->clearMediaCollection('icon');
                $category->addMediaFromRequest('icon')
                    ->toMediaCollection('icon');
            }

            return $this->successResponse(
                new CategoryResource($category),
                'Category updated successfully'
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                'Failed to update category: ' . $e->getMessage(),
                500
            );
        }
    }

    /**
     * Remove the specified category.
     */
    public function destroy(Category $category): JsonResponse
    {
        try {
            if ($category->courses()->count() > 0) {
                return $this->errorResponse(
                    'Cannot delete category with associated courses',
                    400
                );
            }

            $category->clearMediaCollection('icon');
            $category->delete();

            return $this->successResponse(
                null,
                'Category deleted successfully'
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                'Failed to delete category: ' . $e->getMessage(),
                500
            );
        }
    }
}
