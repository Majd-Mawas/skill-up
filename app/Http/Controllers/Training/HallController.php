<?php

namespace App\Http\Controllers\Training;

use App\Http\Controllers\Controller;
use App\Models\Hall;
use App\Models\TrainingCenter;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class HallController extends Controller
{
    /**
     * Display a listing of the halls.
     */
    public function index()
    {
        $halls = Hall::with(['trainingCenter'])
            ->when(request('search'), function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10);

        return view('training.halls.index', compact('halls'));
    }

    /**
     * Show the form for creating a new hall.
     */
    public function create()
    {
        $trainingCenters = TrainingCenter::where('status', 'active')->get();
        return view('training.halls.create', compact('trainingCenters'));
    }

    /**
     * Store a newly created hall in storage.
     *
     * @throws ValidationException
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'capacity' => ['required', 'integer', 'min:1'],
            'price_per_hour' => ['required', 'numeric', 'min:0'],
            'available' => ['boolean'],
            'training_center_id' => ['required', 'exists:training_centers,id'],
            'media' => ['nullable', 'array'],
            'media.*' => ['image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ]);

        $hall = Hall::create($validated);

        if ($request->hasFile('media')) {
            foreach ($request->file('media') as $media) {
                $hall->addMedia($media)
                    ->preservingOriginal()
                    ->toMediaCollection('halls');
            }
        }

        return redirect()
            ->route('halls.index')
            ->with('success', 'Hall created successfully.');
    }

    /**
     * Display the specified hall.
     */
    public function show(Hall $hall)
    {
        $hall->load(['trainingCenter', 'sessions' => function ($query) {
            $query->latest()->paginate(10);
        }]);

        return view('training.halls.show', compact('hall'));
    }

    /**
     * Show the form for editing the specified hall.
     */
    public function edit(Hall $hall)
    {
        $trainingCenters = TrainingCenter::where('status', 'active')->get();
        return view('training.halls.edit', compact('hall', 'trainingCenters'));
    }

    /**
     * Update the specified hall in storage.
     *
     * @throws ValidationException
     */
    public function update(Request $request, Hall $hall)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'capacity' => ['required', 'integer', 'min:1'],
            'price_per_hour' => ['required', 'numeric', 'min:0'],
            'available' => ['boolean'],
            'training_center_id' => ['required', 'exists:training_centers,id'],
            'media' => ['nullable', 'array'],
            'media.*' => ['image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'delete_media' => ['nullable', 'array'],
            'delete_media.*' => ['exists:media,id'],
        ]);

        $hall->update($validated);

        // Handle media deletion
        if ($request->has('delete_media')) {
            $hall->getMedia('halls')
                ->whereIn('id', $request->delete_media)
                ->each(function ($media) {
                    $media->delete();
                });
        }

        // Handle new media uploads
        if ($request->hasFile('media')) {
            foreach ($request->file('media') as $media) {
                $hall->addMedia($media)
                    ->preservingOriginal()
                    ->toMediaCollection('halls');
            }
        }

        return redirect()
            ->route('halls.index')
            ->with('success', 'Hall updated successfully.');
    }

    /**
     * Remove the specified hall from storage.
     */
    public function destroy(Hall $hall)
    {
        if ($hall->sessions()->exists()) {
            return back()->with('error', 'Cannot delete hall with existing sessions.');
        }

        $hall->delete();

        return redirect()
            ->route('halls.index')
            ->with('success', 'Hall deleted successfully.');
    }
}
