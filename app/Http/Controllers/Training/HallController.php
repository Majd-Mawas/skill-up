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
     * Display a listing of the halls for a training center.
     */
    public function index(TrainingCenter $trainingCenter)
    {
        $halls = $trainingCenter->halls()
            ->withCount('sessions')
            ->latest()
            ->paginate(10);

        return view('training.halls.index', compact('trainingCenter', 'halls'));
    }

    /**
     * Show the form for creating a new hall.
     */
    public function create(TrainingCenter $trainingCenter)
    {
        return view('training.halls.create', compact('trainingCenter'));
    }

    /**
     * Store a newly created hall in storage.
     *
     * @throws ValidationException
     */
    public function store(Request $request, TrainingCenter $trainingCenter)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'capacity' => ['required', 'integer', 'min:1'],
            'status' => ['required', 'in:available,maintenance,unavailable'],
            'equipment' => ['nullable', 'array'],
            'equipment.*' => ['string'],
        ]);

        $hall = $trainingCenter->halls()->create($validated);

        return redirect()
            ->route('training-centers.halls.show', [$trainingCenter, $hall])
            ->with('success', 'Hall created successfully.');
    }

    /**
     * Display the specified hall.
     */
    public function show(TrainingCenter $trainingCenter, Hall $hall)
    {
        $hall->load(['sessions' => function ($query) {
            $query->latest()->paginate(10);
        }]);

        return view('training.halls.show', compact('trainingCenter', 'hall'));
    }

    /**
     * Show the form for editing the specified hall.
     */
    public function edit(TrainingCenter $trainingCenter, Hall $hall)
    {
        return view('training.halls.edit', compact('trainingCenter', 'hall'));
    }

    /**
     * Update the specified hall in storage.
     *
     * @throws ValidationException
     */
    public function update(Request $request, TrainingCenter $trainingCenter, Hall $hall)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'capacity' => ['required', 'integer', 'min:1'],
            'status' => ['required', 'in:available,maintenance,unavailable'],
            'equipment' => ['nullable', 'array'],
            'equipment.*' => ['string'],
        ]);

        $hall->update($validated);

        return redirect()
            ->route('training-centers.halls.show', [$trainingCenter, $hall])
            ->with('success', 'Hall updated successfully.');
    }

    /**
     * Remove the specified hall from storage.
     */
    public function destroy(TrainingCenter $trainingCenter, Hall $hall)
    {
        if ($hall->sessions()->exists()) {
            return back()->with('error', 'Cannot delete hall with existing sessions.');
        }

        $hall->delete();

        return redirect()
            ->route('training-centers.halls.index', $trainingCenter)
            ->with('success', 'Hall deleted successfully.');
    }
}
