<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\User;
use App\Enums\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CourseTrainerController extends Controller
{
    /**
     * Display a listing of trainers for a specific online course.
     */
    public function index(string $courseId)
    {
        $course = Course::findOrFail($courseId);
        
        if (!$course->is_online) {
            return response()->json([
                'success' => false,
                'message' => 'This course is not an online course',
            ], 400);
        }
        
        $trainers = $course->trainers()->with('roles')->get();
        
        return response()->json([
            'success' => true,
            'data' => $trainers,
        ]);
    }

    /**
     * Assign a trainer to an online course.
     */
    public function store(Request $request, string $courseId)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'price' => 'nullable|numeric|min:0',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }
        
        $course = Course::findOrFail($courseId);
        
        if (!$course->is_online) {
            return response()->json([
                'success' => false,
                'message' => 'This course is not an online course',
            ], 400);
        }
        
        $user = User::findOrFail($request->user_id);
        
        // Check if user has TRAINER role
        $hasTrainerRole = $user->roles()->where('name', Role::TRAINER->value)->exists();
        
        if (!$hasTrainerRole) {
            return response()->json([
                'success' => false,
                'message' => 'User must have a TRAINER role to be assigned to an online course',
            ], 400);
        }
        
        // Check if trainer is already assigned to this course
        if ($course->trainers()->where('user_id', $user->id)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Trainer is already assigned to this course',
            ], 400);
        }
        
        $course->trainers()->attach($user->id, [
            'price' => $request->price,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'notes' => $request->notes,
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Trainer assigned to course successfully',
            'data' => $course->trainers()->where('user_id', $user->id)->first(),
        ], 201);
    }

    /**
     * Display the specified trainer for a course.
     */
    public function show(string $courseId, string $userId)
    {
        $course = Course::findOrFail($courseId);
        
        if (!$course->is_online) {
            return response()->json([
                'success' => false,
                'message' => 'This course is not an online course',
            ], 400);
        }
        
        $trainer = $course->trainers()->where('user_id', $userId)->first();
        
        if (!$trainer) {
            return response()->json([
                'success' => false,
                'message' => 'Trainer not found for this course',
            ], 404);
        }
        
        return response()->json([
            'success' => true,
            'data' => $trainer,
        ]);
    }

    /**
     * Update the trainer assignment for a course.
     */
    public function update(Request $request, string $courseId, string $userId)
    {
        $validator = Validator::make($request->all(), [
            'price' => 'nullable|numeric|min:0',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }
        
        $course = Course::findOrFail($courseId);
        
        if (!$course->is_online) {
            return response()->json([
                'success' => false,
                'message' => 'This course is not an online course',
            ], 400);
        }
        
        // Check if trainer is assigned to this course
        if (!$course->trainers()->where('user_id', $userId)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Trainer is not assigned to this course',
            ], 404);
        }
        
        $course->trainers()->updateExistingPivot($userId, [
            'price' => $request->price,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'notes' => $request->notes,
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Trainer assignment updated successfully',
            'data' => $course->trainers()->where('user_id', $userId)->first(),
        ]);
    }

    /**
     * Remove the trainer assignment from a course.
     */
    public function destroy(string $courseId, string $userId)
    {
        $course = Course::findOrFail($courseId);
        
        if (!$course->is_online) {
            return response()->json([
                'success' => false,
                'message' => 'This course is not an online course',
            ], 400);
        }
        
        // Check if trainer is assigned to this course
        if (!$course->trainers()->where('user_id', $userId)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Trainer is not assigned to this course',
            ], 404);
        }
        
        $course->trainers()->detach($userId);
        
        return response()->json([
            'success' => true,
            'message' => 'Trainer removed from course successfully',
        ]);
    }
}
