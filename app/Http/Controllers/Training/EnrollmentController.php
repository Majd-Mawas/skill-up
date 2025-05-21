<?php

namespace App\Http\Controllers\Training;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Session;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;

class EnrollmentController extends Controller
{
    /**
     * Display a listing of the enrollments for a course.
     */
    public function index(Course $course)
    {
        $enrollments = $course->enrollments()
            ->with(['user', 'sessions'])
            ->latest()
            ->paginate(10);

        return view('training.enrollments.index', compact('course', 'enrollments'));
    }

    /**
     * Show the form for creating a new enrollment.
     */
    public function create(Course $course)
    {
        return view('training.enrollments.create', compact('course'));
    }

    /**
     * Store a newly created enrollment in storage.
     *
     * @throws ValidationException
     */
    public function store(Request $request, Course $course)
    {
        $validated = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'status' => ['required', 'in:pending,approved,rejected,cancelled'],
            'session_ids' => ['required', 'array'],
            'session_ids.*' => ['required', 'exists:sessions,id'],
        ]);

        // Check if user is already enrolled
        if ($course->enrollments()->where('user_id', $validated['user_id'])->exists()) {
            return back()->with('error', 'User is already enrolled in this course.');
        }

        // Check if sessions belong to the course
        $sessionIds = collect($validated['session_ids']);
        $invalidSessions = $sessionIds->diff($course->sessions()->pluck('id'));
        if ($invalidSessions->isNotEmpty()) {
            return back()->with('error', 'One or more selected sessions do not belong to this course.');
        }

        // Check if sessions are full
        $fullSessions = Session::whereIn('id', $sessionIds)
            ->whereHas('enrollments', function ($query) {
                $query->where('status', 'approved');
            }, '>=', DB::raw('max_students'))
            ->pluck('id');

        if ($fullSessions->isNotEmpty()) {
            return back()->with('error', 'One or more selected sessions are full.');
        }

        $enrollment = $course->enrollments()->create([
            'user_id' => $validated['user_id'],
            'status' => $validated['status'],
        ]);

        $enrollment->sessions()->attach($validated['session_ids']);

        return redirect()
            ->route('web.courses.enrollments.show', [$course, $enrollment])
            ->with('success', 'Enrollment created successfully.');
    }

    /**
     * Display the specified enrollment.
     */
    public function show(Course $course, Enrollment $enrollment)
    {
        $enrollment->load(['user', 'sessions']);

        return view('training.enrollments.show', compact('course', 'enrollment'));
    }

    /**
     * Show the form for editing the specified enrollment.
     */
    public function edit(Course $course, Enrollment $enrollment)
    {
        return view('training.enrollments.edit', compact('course', 'enrollment'));
    }

    /**
     * Update the specified enrollment in storage.
     *
     * @throws ValidationException
     */
    public function update(Request $request, Course $course, Enrollment $enrollment)
    {
        $validated = $request->validate([
            'status' => ['required', 'in:pending,approved,rejected,cancelled'],
            'session_ids' => ['required', 'array'],
            'session_ids.*' => ['required', 'exists:sessions,id'],
        ]);

        // Check if sessions belong to the course
        $sessionIds = collect($validated['session_ids']);
        $invalidSessions = $sessionIds->diff($course->sessions()->pluck('id'));
        if ($invalidSessions->isNotEmpty()) {
            return back()->with('error', 'One or more selected sessions do not belong to this course.');
        }

        // Check if sessions are full (excluding current enrollment)
        $fullSessions = Session::whereIn('id', $sessionIds)
            ->whereHas('enrollments', function ($query) use ($enrollment) {
                $query->where('status', 'approved')
                    ->where('enrollment_id', '!=', $enrollment->id);
            }, '>=', DB::raw('max_students'))
            ->pluck('id');

        if ($fullSessions->isNotEmpty()) {
            return back()->with('error', 'One or more selected sessions are full.');
        }

        $enrollment->update([
            'status' => $validated['status'],
        ]);

        $enrollment->sessions()->sync($validated['session_ids']);

        return redirect()
            ->route('web.courses.enrollments.show', [$course, $enrollment])
            ->with('success', 'Enrollment updated successfully.');
    }

    /**
     * Remove the specified enrollment from storage.
     */
    public function destroy(Course $course, Enrollment $enrollment)
    {
        $enrollment->delete();

        return redirect()
            ->route('web.courses.enrollments.index', $course)
            ->with('success', 'Enrollment deleted successfully.');
    }
}
