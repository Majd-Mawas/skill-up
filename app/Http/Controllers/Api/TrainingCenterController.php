<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\TrainingCenterResource;
use App\Models\TrainingCenter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TrainingCenterController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $trainingCenters = TrainingCenter::with(['area'])->paginate(10);
        return $this->sendResponse(TrainingCenterResource::collection($trainingCenters), 'Training centers retrieved successfully.');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'phone_number' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'area_id' => 'required|exists:areas,id',
            'status' => 'required|in:active,inactive',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors()->toArray(), 422);
        }

        $trainingCenter = TrainingCenter::create($request->all());
        return $this->sendResponse(new TrainingCenterResource($trainingCenter), 'Training center created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(TrainingCenter $trainingCenter)
    {
        return $this->sendResponse(new TrainingCenterResource($trainingCenter->load(['area'])), 'Training center retrieved successfully.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TrainingCenter $trainingCenter)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'address' => 'sometimes|required|string',
            'phone_number' => 'sometimes|required|string|max:20',
            'email' => 'sometimes|required|email|max:255',
            'area_id' => 'sometimes|required|exists:areas,id',
            'status' => 'sometimes|required|in:active,inactive',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors()->toArray(), 422);
        }

        $trainingCenter->update($request->all());
        return $this->sendResponse(new TrainingCenterResource($trainingCenter), 'Training center updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TrainingCenter $trainingCenter)
    {
        $trainingCenter->delete();
        return $this->sendResponse(null, 'Training center deleted successfully.');
    }
}
