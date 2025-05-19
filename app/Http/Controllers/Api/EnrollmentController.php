<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\EnrollmentResource;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EnrollmentController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $enrollments = Enrollment::with(['user', 'course'])->paginate(10);
        return $this->sendResponse(EnrollmentResource::collection($enrollments), 'Enrollments retrieved successfully.');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'course_id' => 'required|exists:courses,id',
            'status' => 'required|in:pending,approved,rejected,completed',
            'enrollment_date' => 'required|date',
            'completion_date' => 'nullable|date|after:enrollment_date',
            'grade' => 'nullable|numeric|min:0|max:100',
            'feedback' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors()->toArray(), 422);
        }

        $enrollment = Enrollment::create($request->all());
        return $this->sendResponse(new EnrollmentResource($enrollment), 'Enrollment created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Enrollment $enrollment)
    {
        return $this->sendResponse(
            new EnrollmentResource($enrollment->load(['user', 'course'])),
            'Enrollment retrieved successfully.'
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Enrollment $enrollment)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'sometimes|required|exists:users,id',
            'course_id' => 'sometimes|required|exists:courses,id',
            'status' => 'sometimes|required|in:pending,approved,rejected,completed',
            'enrollment_date' => 'sometimes|required|date',
            'completion_date' => 'nullable|date|after:enrollment_date',
            'grade' => 'nullable|numeric|min:0|max:100',
            'feedback' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors()->toArray(), 422);
        }

        $enrollment->update($request->all());
        return $this->sendResponse(new EnrollmentResource($enrollment), 'Enrollment updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Enrollment $enrollment)
    {
        $enrollment->delete();
        return $this->sendResponse(null, 'Enrollment deleted successfully.');
    }
}
