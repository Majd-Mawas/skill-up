<?php

namespace App\Http\Controllers\Training;

use App\Http\Controllers\Controller;
use App\Models\Area;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class AreaController extends Controller
{
    /**
     * Display a listing of the areas.
     */
    public function index()
    {
        $areas = Area::withCount('courses')
            ->latest()
            ->paginate(10);

        return view('training.areas.index', compact('areas'));
    }

    /**
     * Show the form for creating a new area.
     */
    public function create()
    {
        return view('training.areas.create');
    }

    /**
     * Store a newly created area in storage.
     *
     * @throws ValidationException
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:areas'],
            'description' => ['required', 'string'],
            'status' => ['required', 'in:active,inactive'],
        ]);

        $area = Area::create($validated);

        return redirect()
            ->route('areas.show', $area)
            ->with('success', 'Area created successfully.');
    }

    /**
     * Display the specified area.
     */
    public function show(Area $area)
    {
        $area->load(['courses' => function ($query) {
            $query->latest()->paginate(10);
        }]);

        return view('training.areas.show', compact('area'));
    }

    /**
     * Show the form for editing the specified area.
     */
    public function edit(Area $area)
    {
        return view('training.areas.edit', compact('area'));
    }

    /**
     * Update the specified area in storage.
     *
     * @throws ValidationException
     */
    public function update(Request $request, Area $area)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:areas,name,' . $area->id],
            'description' => ['required', 'string'],
            'status' => ['required', 'in:active,inactive'],
        ]);

        $area->update($validated);

        return redirect()
            ->route('areas.show', $area)
            ->with('success', 'Area updated successfully.');
    }

    /**
     * Remove the specified area from storage.
     */
    public function destroy(Area $area)
    {
        if ($area->courses()->exists()) {
            return back()->with('error', 'Cannot delete area with existing courses.');
        }

        $area->delete();

        return redirect()
            ->route('areas.index')
            ->with('success', 'Area deleted successfully.');
    }
}
