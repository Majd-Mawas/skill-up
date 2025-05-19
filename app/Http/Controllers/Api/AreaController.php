<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\AreaResource;
use App\Models\Area;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AreaController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $areas = Area::paginate(10);
        return $this->sendResponse(AreaResource::collection($areas), 'Areas retrieved successfully.');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:areas',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors()->toArray(), 422);
        }

        $area = Area::create($request->all());
        return $this->sendResponse(new AreaResource($area), 'Area created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Area $area)
    {
        return $this->sendResponse(new AreaResource($area), 'Area retrieved successfully.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Area $area)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255|unique:areas,name,' . $area->id,
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors()->toArray(), 422);
        }

        $area->update($request->all());
        return $this->sendResponse(new AreaResource($area), 'Area updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Area $area)
    {
        $area->delete();
        return $this->sendResponse(null, 'Area deleted successfully.');
    }
}
