<?php

namespace App\Http\Controllers\Training;

use App\Http\Controllers\Controller;
use App\Models\TrainingCenter;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class TrainingCenterController extends Controller
{
    /**
     * Display a listing of the training centers.
     */
    public function index()
    {
        $trainingCenters = TrainingCenter::withCount(['courses', 'halls'])
            ->latest()
            ->paginate(10);

        return view('training.centers.index', compact('trainingCenters'));
    }

    /**
     * Show the form for creating a new training center.
     */
    public function create()
    {
        return view('training.centers.create');
    }

    /**
     * Store a newly created training center in storage.
     *
     * @throws ValidationException
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:training_centers'],
            'description' => ['required', 'string'],
            'address' => ['required', 'string'],
            'phone' => ['required', 'string', 'max:20'],
            'email' => ['required', 'email', 'max:255'],
            'status' => ['required', 'in:active,inactive'],
            'capacity' => ['required', 'integer', 'min:1'],
        ]);

        $trainingCenter = TrainingCenter::create($validated);

        return redirect()
            ->route('training-centers.show', $trainingCenter)
            ->with('success', 'Training center created successfully.');
    }

    /**
     * Display the specified training center.
     */
    public function show(TrainingCenter $trainingCenter)
    {
        $trainingCenter->load(['courses' => function ($query) {
            $query->latest()->paginate(10);
        }, 'halls' => function ($query) {
            $query->latest()->paginate(10);
        }]);

        return view('training.centers.show', compact('trainingCenter'));
    }

    /**
     * Show the form for editing the specified training center.
     */
    public function edit(TrainingCenter $trainingCenter)
    {
        return view('training.centers.edit', compact('trainingCenter'));
    }

    /**
     * Update the specified training center in storage.
     *
     * @throws ValidationException
     */
    public function update(Request $request, TrainingCenter $trainingCenter)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:training_centers,name,' . $trainingCenter->id],
            'description' => ['required', 'string'],
            'address' => ['required', 'string'],
            'phone' => ['required', 'string', 'max:20'],
            'email' => ['required', 'email', 'max:255'],
            'status' => ['required', 'in:active,inactive'],
            'capacity' => ['required', 'integer', 'min:1'],
        ]);

        $trainingCenter->update($validated);

        return redirect()
            ->route('training-centers.show', $trainingCenter)
            ->with('success', 'Training center updated successfully.');
    }

    /**
     * Remove the specified training center from storage.
     */
    public function destroy(TrainingCenter $trainingCenter)
    {
        if ($trainingCenter->courses()->exists() || $trainingCenter->halls()->exists()) {
            return back()->with('error', 'Cannot delete training center with existing courses or halls.');
        }

        $trainingCenter->delete();

        return redirect()
            ->route('training-centers.index')
            ->with('success', 'Training center deleted successfully.');
    }
}
