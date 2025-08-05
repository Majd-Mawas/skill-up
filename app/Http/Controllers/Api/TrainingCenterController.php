<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Institute\StoreInstituteRequest;
use App\Http\Resources\TrainingCenterResource;
use App\Models\TrainingCenter;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TrainingCenterController extends Controller
{
    /**
     * Display a listing of institutes.
     */
    public function index(Request $request): JsonResponse
    {
        $query = TrainingCenter::with(['area'])
            ->withCount(['courses', 'reviews']);

        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $query->search($request->search);
        }

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Filter by area
        if ($request->has('area_id')) {
            $query->where('area_id', $request->area_id);
        }

        $institutes = $query->paginate($request->get('per_page', 15));

        return $this->successResponse(
            TrainingCenterResource::collection($institutes),
            'Institutes retrieved successfully'
        );
    }

    /**
     * Store a newly created institute.
     */
    public function store(StoreInstituteRequest $request): JsonResponse
    {
        try {
            $institute = TrainingCenter::create($request->validated());

            // Handle logo upload
            if ($request->hasFile('logo')) {
                $institute->addMediaFromRequest('logo')
                    ->toMediaCollection('logo');
            }

            // Handle gallery uploads
            if ($request->hasFile('gallery')) {
                foreach ($request->file('gallery') as $file) {
                    $institute->addMedia($file)
                        ->toMediaCollection('gallery');
                }
            }

            $institute->load(['area']);

            return $this->successResponse(
                new TrainingCenterResource($institute),
                'Institute created successfully',
                201
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                'Failed to create institute: ' . $e->getMessage(),
                500
            );
        }
    }

    /**
     * Display the specified institute.
     */
    public function show(TrainingCenter $trainingCenter): JsonResponse
    {
        $trainingCenter->load(['area', 'courses.category'])
            ->loadCount(['courses', 'reviews']);

        return $this->successResponse(
            new TrainingCenterResource($trainingCenter),
            'Institute retrieved successfully'
        );
    }

    /**
     * Update the specified institute.
     */
    public function update(StoreInstituteRequest $request, TrainingCenter $trainingCenter): JsonResponse
    {
        try {
            $trainingCenter->update($request->validated());

            // Handle logo upload
            if ($request->hasFile('logo')) {
                $trainingCenter->clearMediaCollection('logo');
                $trainingCenter->addMediaFromRequest('logo')
                    ->toMediaCollection('logo');
            }

            // Handle gallery uploads
            if ($request->hasFile('gallery')) {
                foreach ($request->file('gallery') as $file) {
                    $trainingCenter->addMedia($file)
                        ->toMediaCollection('gallery');
                }
            }

            $trainingCenter->load(['area']);

            return $this->successResponse(
                new TrainingCenterResource($trainingCenter),
                'Institute updated successfully'
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                'Failed to update institute: ' . $e->getMessage(),
                500
            );
        }
    }

    /**
     * Remove the specified institute.
     */
    public function destroy(TrainingCenter $trainingCenter): JsonResponse
    {
        try {
            $trainingCenter->clearMediaCollection('logo');
            $trainingCenter->clearMediaCollection('gallery');
            $trainingCenter->delete();

            return $this->successResponse(
                null,
                'Institute deleted successfully'
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                'Failed to delete institute: ' . $e->getMessage(),
                500
            );
        }
    }
}
