<?php

namespace App\Http\Controllers\Training;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class CategoryController extends Controller
{
    /**
     * Display a listing of the categories.
     */
    public function index()
    {
        $categories = Category::withCount('courses')
            ->latest()
            ->paginate(10);

        return view('training.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new category.
     */
    public function create()
    {
        return view('training.categories.create');
    }

    /**
     * Store a newly created category in storage.
     *
     * @throws ValidationException
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:categories'],
            'description' => ['required', 'string'],
            'status' => ['required', 'in:active,inactive'],
        ]);

        $category = Category::create($validated);

        return redirect()
            ->route('categories.show', $category)
            ->with('success', 'Category created successfully.');
    }

    /**
     * Display the specified category.
     */
    public function show(Category $category)
    {
        $category->load(['courses' => function ($query) {
            $query->latest()->paginate(10);
        }]);

        return view('training.categories.show', compact('category'));
    }

    /**
     * Show the form for editing the specified category.
     */
    public function edit(Category $category)
    {
        return view('training.categories.edit', compact('category'));
    }

    /**
     * Update the specified category in storage.
     *
     * @throws ValidationException
     */
    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:categories,name,' . $category->id],
            'description' => ['required', 'string'],
            'status' => ['required', 'in:active,inactive'],
        ]);

        $category->update($validated);

        return redirect()
            ->route('categories.show', $category)
            ->with('success', 'Category updated successfully.');
    }

    /**
     * Remove the specified category from storage.
     */
    public function destroy(Category $category)
    {
        if ($category->courses()->exists()) {
            return back()->with('error', 'Cannot delete category with existing courses.');
        }

        $category->delete();

        return redirect()
            ->route('categories.index')
            ->with('success', 'Category deleted successfully.');
    }
}
