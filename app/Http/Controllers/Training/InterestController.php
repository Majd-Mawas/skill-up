<?php

namespace App\Http\Controllers\Training;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Interest;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class InterestController extends Controller
{
    /**
     * Display a listing of the interests.
     */
    public function index()
    {
        $interests = Interest::withCount(['users', 'categories'])
            ->with('categories')
            ->latest()
            ->paginate(10);

        return view('training.interests.index', compact('interests'));
    }

    /**
     * Show the form for creating a new interest.
     */
    public function create()
    {
        $categories = Category::all();
        return view('training.interests.create', compact('categories'));
    }

    /**
     * Store a newly created interest in storage.
     *
     * @throws ValidationException
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:interests'],
            'description' => ['required', 'string'],
            'is_active' => ['boolean'],
            'categories' => ['array'],
            'categories.*' => ['exists:categories,id'],
        ]);

        // Set default value for is_active if not provided
        if (!isset($validated['is_active'])) {
            $validated['is_active'] = true;
        }

        $interest = Interest::create($validated);
        
        // Sync categories if provided
        if (isset($request->categories)) {
            $interest->categories()->sync($request->categories);
        }

        return redirect()
            ->route('web.interests.index')
            ->with('success', 'Interest created successfully.');
    }

    /**
     * Display the specified interest.
     */
    public function show(Interest $interest)
    {
        $interest->load([
            'users' => function ($query) {
                $query->latest()->paginate(10);
            },
            'categories'
        ]);

        return view('training.interests.show', compact('interest'));
    }

    /**
     * Show the form for editing the specified interest.
     */
    public function edit(Interest $interest)
    {
        $categories = Category::all();
        $interest->load('categories');
        return view('training.interests.edit', compact('interest', 'categories'));
    }

    /**
     * Update the specified interest in storage.
     *
     * @throws ValidationException
     */
    public function update(Request $request, Interest $interest)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:interests,name,' . $interest->id],
            'description' => ['required', 'string'],
            'is_active' => ['boolean'],
            'categories' => ['array'],
            'categories.*' => ['exists:categories,id'],
        ]);

        // Handle checkbox for is_active
        $validated['is_active'] = $request->has('is_active');

        $interest->update($validated);
        
        // Sync categories
        $interest->categories()->sync($request->categories ?? []);

        return redirect()
            ->route('web.interests.index')
            ->with('success', 'Interest updated successfully.');
    }

    /**
     * Remove the specified interest from storage.
     */
    public function destroy(Interest $interest)
    {
        // Check if the interest has associated users
        if ($interest->users()->exists()) {
            return back()->with('error', 'Cannot delete interest with associated users.');
        }

        $interest->delete();

        return redirect()
            ->route('web.interests.index')
            ->with('success', 'Interest deleted successfully.');
    }
}