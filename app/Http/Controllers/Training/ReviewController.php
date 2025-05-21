<?php

namespace App\Http\Controllers\Training;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ReviewController extends Controller
{
    /**
     * Display a listing of the reviews for a course.
     */
    public function index(Course $course)
    {
        $reviews = $course->reviews()
            ->with(['user'])
            ->latest()
            ->paginate(10);

        return view('training.reviews.index', compact('course', 'reviews'));
    }

    /**
     * Show the form for creating a new review.
     */
    public function create(Course $course)
    {
        // Check if user has completed the course
        $enrollment = $course->enrollments()
            ->where('user_id', auth()->id())
            ->where('status', 'approved')
            ->first();

        if (!$enrollment) {
            return back()->with('error', 'You must be enrolled and approved in the course to leave a review.');
        }

        // Check if user has already reviewed the course
        if ($course->reviews()->where('user_id', auth()->id())->exists()) {
            return back()->with('error', 'You have already reviewed this course.');
        }

        return view('training.reviews.create', compact('course'));
    }

    /**
     * Store a newly created review in storage.
     *
     * @throws ValidationException
     */
    public function store(Request $request, Course $course)
    {
        // Check if user has completed the course
        $enrollment = $course->enrollments()
            ->where('user_id', auth()->id())
            ->where('status', 'approved')
            ->first();

        if (!$enrollment) {
            return back()->with('error', 'You must be enrolled and approved in the course to leave a review.');
        }

        // Check if user has already reviewed the course
        if ($course->reviews()->where('user_id', auth()->id())->exists()) {
            return back()->with('error', 'You have already reviewed this course.');
        }

        $validated = $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['required', 'string', 'min:10'],
        ]);

        $review = $course->reviews()->create([
            'user_id' => auth()->id(),
            'rating' => $validated['rating'],
            'comment' => $validated['comment'],
        ]);

        return redirect()
            ->route('web.courses.reviews.show', [$course, $review])
            ->with('success', 'Review created successfully.');
    }

    /**
     * Display the specified review.
     */
    public function show(Course $course, Review $review)
    {
        $review->load('user');

        return view('training.reviews.show', compact('course', 'review'));
    }

    /**
     * Show the form for editing the specified review.
     */
    public function edit(Course $course, Review $review)
    {
        // Check if user owns the review
        if ($review->user_id !== auth()->id()) {
            return back()->with('error', 'You can only edit your own reviews.');
        }

        return view('training.reviews.edit', compact('course', 'review'));
    }

    /**
     * Update the specified review in storage.
     *
     * @throws ValidationException
     */
    public function update(Request $request, Course $course, Review $review)
    {
        // Check if user owns the review
        if ($review->user_id !== auth()->id()) {
            return back()->with('error', 'You can only edit your own reviews.');
        }

        $validated = $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['required', 'string', 'min:10'],
        ]);

        $review->update($validated);

        return redirect()
            ->route('web.courses.reviews.show', [$course, $review])
            ->with('success', 'Review updated successfully.');
    }

    /**
     * Remove the specified review from storage.
     */
    public function destroy(Course $course, Review $review)
    {
        // Check if user owns the review
        if ($review->user_id !== auth()->id()) {
            return back()->with('error', 'You can only delete your own reviews.');
        }

        $review->delete();

        return redirect()
            ->route('web.courses.reviews.index', $course)
            ->with('success', 'Review deleted successfully.');
    }
}
