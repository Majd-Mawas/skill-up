<?php

namespace App\Http\Controllers\Training;

use App\Http\Controllers\Controller;
use App\Models\TrainingCenter;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;

class TrainingCenterController extends Controller
{
    /**
     * Display a listing of the training centers.
     */
    public function index()
    {
        $trainingCenters = TrainingCenter::with(['area'])
            ->when(request('search'), function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('address', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10);

        return view('training.training-centers.index', compact('trainingCenters'));
    }

    /**
     * Show the form for creating a new training center.
     */
    public function create()
    {
        $areas = \App\Models\Area::all();
        return view('training.training-centers.create', compact('areas'));
    }

    /**
     * Store a newly created training center in storage.
     *
     * @throws ValidationException
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string'],
            'phone_number' => ['required', 'string', 'max:20'],
            'email' => ['required', 'email', 'max:255'],
            'area_id' => ['required', 'exists:areas,id'],
            'status' => ['required', 'string', 'in:active,inactive'],
            'logo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:10048'],
        ]);

        $trainingCenter = TrainingCenter::create($validated);

        // Handle logo upload
        if ($request->hasFile('logo')) {
            $trainingCenter->addMediaFromRequest('logo')
                ->toMediaCollection('logo');
        }

        return redirect()
            ->route('web.training-centers.index', $trainingCenter)
            ->with('success', 'Training center created successfully.');
    }

    /**
     * Display the specified training center.
     */
    public function show(TrainingCenter $trainingCenter)
    {

        $trainingCenter->load(['area', 'halls', 'courses']);
        // return $trainingCenter->placementTestBookings->load(['user']);

        return view('training.training-centers.show', compact('trainingCenter'));
    }

    /**
     * Show the form for editing the specified training center.
     */
    public function edit(TrainingCenter $trainingCenter)
    {
        $areas = \App\Models\Area::all();
        return view('training.training-centers.edit', compact('trainingCenter', 'areas'));
    }

    /**
     * Update the specified training center in storage.
     *
     * @throws ValidationException
     */
    public function update(Request $request, TrainingCenter $trainingCenter)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string'],
            'phone_number' => ['required', 'string', 'max:20'],
            'email' => ['required', 'email', 'max:255'],
            'area_id' => ['required', 'exists:areas,id'],
            'status' => ['required', 'string', 'in:active,inactive'],
            'logo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:10048'],
        ]);

        $trainingCenter->update($validated);

        // Handle logo upload
        if ($request->hasFile('logo')) {
            // Remove existing logo if any
            $trainingCenter->clearMediaCollection('logo');
            // Add new logo
            $trainingCenter->addMediaFromRequest('logo')
                ->toMediaCollection('logo');
        }

        return redirect()
            ->route('web.training-centers.show', $trainingCenter)
            ->with('success', 'Training center updated successfully.');
    }

    /**
     * Remove the specified training center from storage.
     */
    public function destroy(TrainingCenter $trainingCenter)
    {
        if ($trainingCenter->halls()->exists()) {
            return back()->with('error', 'Cannot delete training center with existing halls.');
        }

        $trainingCenter->delete();

        return redirect()
            ->route('web.training-centers.index')
            ->with('success', 'Training center deleted successfully.');
    }

    /**
     * Export training center bookings to Excel.
     */
    public function export(TrainingCenter $trainingCenter)
    {
        return Excel::download(new \App\Exports\TrainingCenterBookingsExport($trainingCenter), $trainingCenter->name . ' - Bookings.xlsx');
    }
}
