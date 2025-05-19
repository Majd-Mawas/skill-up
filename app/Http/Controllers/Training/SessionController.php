<?php

namespace App\Http\Controllers\Training;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Session;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class SessionController extends Controller
{
    /**
     * Display a listing of the sessions for a course.
     */
    public function index(Course $course)
    {
        $sessions = $course->sessions()
            ->with(['instructor'])
            ->latest()
            ->paginate(10);

        return view('training.sessions.index', compact('course', 'sessions'));
    }

    /**
     * Show the form for creating a new session.
     */
    public function create(Course $course)
    {
        return view('training.sessions.create', compact('course'));
    }

    /**
     * Store a newly created session in storage.
     *
     * @throws ValidationException
     */
    public function store(Request $request, Course $course)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'instructor_id' => ['required', 'exists:users,id'],
            'start_time' => ['required', 'date', 'after:now'],
            'end_time' => ['required', 'date', 'after:start_time'],
            'status' => ['required', 'in:scheduled,in_progress,completed,cancelled'],
            'max_students' => ['required', 'integer', 'min:1'],
        ]);

        $session = $course->sessions()->create($validated);

        return redirect()
            ->route('courses.sessions.show', [$course, $session])
            ->with('success', 'Session created successfully.');
    }

    /**
     * Display the specified session.
     */
    public function show(Course $course, Session $session)
    {
        $session->load(['instructor', 'enrollments']);

        return view('training.sessions.show', compact('course', 'session'));
    }

    /**
     * Show the form for editing the specified session.
     */
    public function edit(Course $course, Session $session)
    {
        return view('training.sessions.edit', compact('course', 'session'));
    }

    /**
     * Update the specified session in storage.
     *
     * @throws ValidationException
     */
    public function update(Request $request, Course $course, Session $session)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'instructor_id' => ['required', 'exists:users,id'],
            'start_time' => ['required', 'date'],
            'end_time' => ['required', 'date', 'after:start_time'],
            'status' => ['required', 'in:scheduled,in_progress,completed,cancelled'],
            'max_students' => ['required', 'integer', 'min:1'],
        ]);

        $session->update($validated);

        return redirect()
            ->route('courses.sessions.show', [$course, $session])
            ->with('success', 'Session updated successfully.');
    }

    /**
     * Remove the specified session from storage.
     */
    public function destroy(Course $course, Session $session)
    {
        if ($session->enrollments()->exists()) {
            return back()->with('error', 'Cannot delete session with existing enrollments.');
        }

        $session->delete();

        return redirect()
            ->route('courses.sessions.index', $course)
            ->with('success', 'Session deleted successfully.');
    }
}
