<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\HallResource;
use App\Models\Hall;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class HallController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $halls = Hall::with(['trainingCenter'])->paginate(10);
        return $this->sendResponse(HallResource::collection($halls), 'Halls retrieved successfully.');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'capacity' => 'required|integer|min:1',
            'price_per_hour' => 'required|numeric|min:0',
            'available' => 'boolean',
            'training_center_id' => 'required|exists:training_centers,id',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors()->toArray(), 422);
        }

        $hall = Hall::create($request->all());
        return $this->sendResponse(new HallResource($hall), 'Hall created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Hall $hall)
    {
        return $this->sendResponse(
            new HallResource($hall->load(['trainingCenter'])),
            'Hall retrieved successfully.'
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Hall $hall)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'capacity' => 'sometimes|required|integer|min:1',
            'price_per_hour' => 'sometimes|required|numeric|min:0',
            'available' => 'boolean',
            'training_center_id' => 'sometimes|required|exists:training_centers,id',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors()->toArray(), 422);
        }

        $hall->update($request->all());
        return $this->sendResponse(new HallResource($hall), 'Hall updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Hall $hall)
    {
        if ($hall->sessions()->exists()) {
            return $this->sendError('Cannot delete hall with existing sessions.', [], 422);
        }

        $hall->delete();
        return $this->sendResponse(null, 'Hall deleted successfully.');
    }
}
