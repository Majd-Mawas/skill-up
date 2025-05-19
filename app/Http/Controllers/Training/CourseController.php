<?php

namespace App\Http\Controllers\Training;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class CourseController extends Controller
{
    /**
     * Display a listing of the courses.
     */
    public function index()
    {
        $courses = Course::with(['category', 'area', 'sessions'])
            ->latest()
            ->paginate(10);

        return view('training.courses.index', compact('courses'));
    }

    /**
     * Show the form for creating a new course.
     */
    public function create()
    {
        return view('training.courses.create');
    }

    /**
     * Store a newly created course in storage.
     *
     * @throws ValidationException
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'category_id' => ['required', 'exists:categories,id'],
            'area_id' => ['required', 'exists:areas,id'],
            'price' => ['required', 'numeric', 'min:0'],
            'duration' => ['required', 'integer', 'min:1'],
            'max_students' => ['required', 'integer', 'min:1'],
            'start_date' => ['required', 'date', 'after:today'],
            'end_date' => ['required', 'date', 'after:start_date'],
            'status' => ['required', 'in:draft,published,completed,cancelled'],
        ]);

        $course = Course::create($validated);

        return redirect()
            ->route('courses.show', $course)
            ->with('success', 'Course created successfully.');
    }

    /**
     * Display the specified course.
     */
    public function show(Course $course)
    {
        $course->load(['category', 'area', 'sessions', 'enrollments']);

        return view('training.courses.show', compact('course'));
    }

    /**
     * Show the form for editing the specified course.
     */
    public function edit(Course $course)
    {
        return view('training.courses.edit', compact('course'));
    }

    /**
     * Update the specified course in storage.
     *
     * @throws ValidationException
     */
    public function update(Request $request, Course $course)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'category_id' => ['required', 'exists:categories,id'],
            'area_id' => ['required', 'exists:areas,id'],
            'price' => ['required', 'numeric', 'min:0'],
            'duration' => ['required', 'integer', 'min:1'],
            'max_students' => ['required', 'integer', 'min:1'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after:start_date'],
            'status' => ['required', 'in:draft,published,completed,cancelled'],
        ]);

        $course->update($validated);

        return redirect()
            ->route('courses.show', $course)
            ->with('success', 'Course updated successfully.');
    }

    /**
     * Remove the specified course from storage.
     */
    public function destroy(Course $course)
    {
        if ($course->enrollments()->exists()) {
            return back()->with('error', 'Cannot delete course with existing enrollments.');
        }

        $course->delete();

        return redirect()
            ->route('courses.index')
            ->with('success', 'Course deleted successfully.');
    }
}
