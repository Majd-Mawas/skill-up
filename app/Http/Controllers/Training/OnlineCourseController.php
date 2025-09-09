<?php

namespace App\Http\Controllers\Training;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\OnlineCourseBooking;
use App\Models\User;
use App\Enums\Role;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class OnlineCourseController extends Controller
{
    /**
     * Display a listing of the online courses.
     */
    public function index()
    {
        $courses = Course::with(['category', 'trainers'])
            ->where('is_online', true)
            ->when(request('search'), function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10);

        return view('training.online-courses.index', compact('courses'));
    }

    /**
     * Show the form for creating a new online course.
     */
    public function create()
    {
        $categories = \App\Models\Category::all();
        $trainers = User::whereHas('roles', function($query) {
            $query->where('name', Role::TRAINER->value);
        })->get();
        
        return view('training.online-courses.create', compact('categories', 'trainers'));
    }

    /**
     * Store a newly created online course in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'required|string',
                'category_id' => 'required|exists:categories,id',
                'duration_hours' => 'required|integer|min:1',
                'difficulty_level' => 'required|string|in:beginner,intermediate,advanced',
                'prerequisites' => 'nullable|array',
                'learning_outcomes' => 'nullable|array',
                'trainers' => 'required|array|min:1',
                'trainers.*' => 'exists:users,id',
                'prices' => 'required|array|min:1',
                'prices.*' => 'numeric|min:0',
                'start_dates' => 'required|array|min:1',
                'start_dates.*' => 'date',
                'end_dates' => 'required|array|min:1',
                'end_dates.*' => 'date|after_or_equal:start_dates.*',
                'notes' => 'nullable|array',
                'notes.*' => 'nullable|string',
            ]);

            // Create the course with is_online set to true
            $course = Course::create([
                'name' => $validated['name'],
                'description' => $validated['description'],
                'category_id' => $validated['category_id'],
                'duration_hours' => $validated['duration_hours'],
                'difficulty_level' => $validated['difficulty_level'],
                'prerequisites' => $validated['prerequisites'] ?? [],
                'learning_outcomes' => $validated['learning_outcomes'] ?? [],
                'is_online' => true,
            ]);

            // Attach trainers with their respective prices and dates
            foreach ($validated['trainers'] as $key => $trainerId) {
                $course->trainers()->attach($trainerId, [
                    'price' => $validated['prices'][$key],
                    'start_date' => $validated['start_dates'][$key],
                    'end_date' => $validated['end_dates'][$key],
                    'notes' => $validated['notes'][$key] ?? null,
                ]);
            }

            // Handle course image upload if provided
            if ($request->hasFile('image') && $request->file('image')->isValid()) {
                $course->addMediaFromRequest('image')
                    ->toMediaCollection('course_image');
            }

            return redirect()->route('web.online-courses.index')
                ->with('success', 'Online course created successfully.');
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to create online course: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified online course.
     */
    public function show(Course $onlineCourse)
    {
        if (!$onlineCourse->is_online) {
            abort(404, 'This is not an online course');
        }
        
        $onlineCourse->load(['category', 'trainers']);
        $bookings = OnlineCourseBooking::where('course_id', $onlineCourse->id)
            ->with(['user', 'trainer'])
            ->latest()
            ->paginate(10);
            
        return view('training.online-courses.show', compact('onlineCourse', 'bookings'));
    }

    /**
     * Show the form for editing the specified online course.
     */
    public function edit(Course $onlineCourse)
    {
        if (!$onlineCourse->is_online) {
            abort(404, 'This is not an online course');
        }
        
        $categories = \App\Models\Category::all();
        $trainers = User::whereHas('roles', function($query) {
            $query->where('name', Role::TRAINER->value);
        })->get();
        $onlineCourse->load('trainers');
        
        return view('training.online-courses.edit', compact('onlineCourse', 'categories', 'trainers'));
    }

    /**
     * Update the specified online course in storage.
     */
    public function update(Request $request, Course $onlineCourse)
    {
        if (!$onlineCourse->is_online) {
            abort(404, 'This is not an online course');
        }
        
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'required|string',
                'category_id' => 'required|exists:categories,id',
                'duration_hours' => 'required|integer|min:1',
                'difficulty_level' => 'required|string|in:beginner,intermediate,advanced',
                'prerequisites' => 'nullable|array',
                'learning_outcomes' => 'nullable|array',
                'trainers' => 'required|array|min:1',
                'trainers.*' => 'exists:users,id',
                'prices' => 'required|array|min:1',
                'prices.*' => 'numeric|min:0',
                'start_dates' => 'required|array|min:1',
                'start_dates.*' => 'date',
                'end_dates' => 'required|array|min:1',
                'end_dates.*' => 'date|after_or_equal:start_dates.*',
                'notes' => 'nullable|array',
                'notes.*' => 'nullable|string',
            ]);

            // Update the course
            $onlineCourse->update([
                'name' => $validated['name'],
                'description' => $validated['description'],
                'category_id' => $validated['category_id'],
                'duration_hours' => $validated['duration_hours'],
                'difficulty_level' => $validated['difficulty_level'],
                'prerequisites' => $validated['prerequisites'] ?? [],
                'learning_outcomes' => $validated['learning_outcomes'] ?? [],
            ]);

            // Sync trainers with their respective prices and dates
            $onlineCourse->trainers()->detach();
            foreach ($validated['trainers'] as $key => $trainerId) {
                $onlineCourse->trainers()->attach($trainerId, [
                    'price' => $validated['prices'][$key],
                    'start_date' => $validated['start_dates'][$key],
                    'end_date' => $validated['end_dates'][$key],
                    'notes' => $validated['notes'][$key] ?? null,
                ]);
            }

            // Handle course image upload if provided
            if ($request->hasFile('image') && $request->file('image')->isValid()) {
                // Remove old image first
                $onlineCourse->clearMediaCollection('course_image');
                // Add new image
                $onlineCourse->addMediaFromRequest('image')
                    ->toMediaCollection('course_image');
            }

            return redirect()->route('web.online-courses.index')
                ->with('success', 'Online course updated successfully.');
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to update online course: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified online course from storage.
     */
    public function destroy(Course $onlineCourse)
    {
        if (!$onlineCourse->is_online) {
            abort(404, 'This is not an online course');
        }
        
        try {
            // Check if there are any bookings for this course
            if ($onlineCourse->onlineCourseBookings()->exists()) {
                return back()->with('error', 'Cannot delete online course with existing bookings.');
            }
            
            // Detach trainers
            $onlineCourse->trainers()->detach();
            
            // Delete the course
            $onlineCourse->delete();
            
            return redirect()->route('web.online-courses.index')
                ->with('success', 'Online course deleted successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to delete online course: ' . $e->getMessage());
        }
    }
}