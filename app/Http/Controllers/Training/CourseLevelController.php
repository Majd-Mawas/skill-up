<?php

namespace App\Http\Controllers\Training;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseLevel;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class CourseLevelController extends Controller
{
    /**
     * Display a listing of the course levels.
     */
    public function index(Course $course)
    {
        $levels = $course->levels()->paginate(10);
        return view('training.courses.levels.index', compact('course', 'levels'));
    }

    /**
     * Show the form for creating a new course level.
     */
    public function create(Course $course)
    {
        $levels = $course->levels()->count();

        return view('training.courses.levels.create', compact('course', 'levels'));
    }

    /**
     * Store a newly created course level in storage.
     *
     * @throws ValidationException
     */
    public function store(Request $request, Course $course)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'level_order' => ['required', 'integer', 'min:1'],
            'is_active' => ['boolean'],
        ]);

        // Check if the level order is already taken
        if ($course->levels()->where('level_order', $validated['level_order'])->exists()) {
            throw ValidationException::withMessages([
                'level_order' => ['This level order is already taken for this course.'],
            ]);
        }

        $course->levels()->create($validated);

        return redirect()
            ->route('web.courses.levels.index', $course)
            ->with('success', 'Course level created successfully.');
    }

    /**
     * Show the form for editing the specified course level.
     */
    public function edit(Course $course, CourseLevel $level)
    {
        return view('training.courses.levels.edit', compact('course', 'level'));
    }

    /**
     * Update the specified course level in storage.
     *
     * @throws ValidationException
     */
    public function update(Request $request, Course $course, CourseLevel $level)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'level_order' => ['required', 'integer', 'min:1'],
            'is_active' => ['boolean'],
        ]);

        // Check if the level order is already taken by another level
        if ($course->levels()
            ->where('level_order', $validated['level_order'])
            ->where('id', '!=', $level->id)
            ->exists()
        ) {
            throw ValidationException::withMessages([
                'level_order' => ['This level order is already taken for this course.'],
            ]);
        }

        $level->update($validated);

        return redirect()
            ->route('web.courses.levels.index', $course)
            ->with('success', 'Course level updated successfully.');
    }

    /**
     * Remove the specified course level from storage.
     */
    public function destroy(Course $course, CourseLevel $level)
    {
        // Check if there are any enrollments or progress records for this level
        if ($level->enrollments()->exists() || $level->progress()->exists()) {
            return back()->with('error', 'Cannot delete level with existing enrollments or progress records.');
        }

        $level->delete();

        return redirect()
            ->route('web.courses.levels.index', $course)
            ->with('success', 'Course level deleted successfully.');
    }

    /**
     * Reorder the levels of a course.
     */
    public function reorder(Request $request, Course $course)
    {
        $validated = $request->validate([
            'levels' => ['required', 'array'],
            'levels.*.id' => ['required', 'exists:course_levels,id'],
            'levels.*.order' => ['required', 'integer', 'min:1'],
        ]);

        foreach ($validated['levels'] as $levelData) {
            $level = $course->levels()->find($levelData['id']);
            if ($level) {
                $level->update(['level_order' => $levelData['order']]);
            }
        }

        return response()->json(['message' => 'Levels reordered successfully']);
    }
}
