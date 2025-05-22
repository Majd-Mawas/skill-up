<?php

namespace App\Http\Controllers\Web;

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
            ->when(request('search'), function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10);

        return view('web.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new category.
     */
    public function create()
    {
        return view('web.categories.create');
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
        ]);

        $category = Category::create($validated);

        return redirect()
            ->route('web.categories.index')
            ->with('success', 'Category created successfully.');
    }

    /**
     * Show the form for editing the specified category.
     */
    public function edit(Category $category)
    {
        return view('web.categories.edit', compact('category'));
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
        ]);

        $category->update($validated);

        return redirect()
            ->route('web.categories.index')
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
            ->route('web.categories.index')
            ->with('success', 'Category deleted successfully.');
    }
}
