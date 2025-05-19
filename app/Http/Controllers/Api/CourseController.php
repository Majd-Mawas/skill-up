<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\CourseResource;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CourseController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $courses = Course::with(['category', 'trainingCenter'])->paginate(10);
        return $this->sendResponse(CourseResource::collection($courses), 'Courses retrieved successfully.');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'training_center_id' => 'required|exists:training_centers,id',
            'price' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors()->toArray(), 422);
        }

        $course = Course::create($request->all());
        return $this->sendResponse(new CourseResource($course), 'Course created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Course $course)
    {
        return $this->sendResponse(
            new CourseResource($course->load(['category', 'trainingCenter'])),
            'Course retrieved successfully.'
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Course $course)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'sometimes|required|exists:categories,id',
            'training_center_id' => 'sometimes|required|exists:training_centers,id',
            'price' => 'sometimes|required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors()->toArray(), 422);
        }

        $course->update($request->all());
        return $this->sendResponse(new CourseResource($course), 'Course updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Course $course)
    {
        $course->delete();
        return $this->sendResponse(null, 'Course deleted successfully.');
    }
}
