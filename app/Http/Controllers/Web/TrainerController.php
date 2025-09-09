<?php

namespace App\Http\Controllers\Web;

use App\Enums\Role;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class TrainerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $trainers = User::with('roles')
            ->whereHas('roles', function ($query) {
                $query->where('name', Role::TRAINER->value);
            })
            ->latest()
            ->paginate(10);

        return view('training.trainers.index', compact('trainers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('training.trainers.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'phone_number' => 'required|string|max:20',
            'gender' => 'required|string|in:male,female',
            'study' => 'nullable|string|max:255',
            'area_id' => 'nullable|exists:areas,id',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        $user = User::create($validated);

        $user->roles()->attach(2);

        if ($request->hasFile('avatar')) {
            $user->addMediaFromRequest('avatar')->toMediaCollection('avatar');
        }

        return redirect()->route('web.trainers.index')
            ->with('success', 'Trainer created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $trainer)
    {
        // Load trainer with relevant relationships
        $trainer->load([
            'onlineCourses',
            'sessions'
        ]);
        
        return view('training.trainers.show', compact('trainer'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $trainer)
    {
        return view('training.trainers.edit', compact('trainer'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $trainer)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $trainer->id,
            'phone_number' => 'required|string|max:20',
            'gender' => 'required|string|in:male,female',
            'study' => 'nullable|string|max:255',
            'area_id' => 'nullable|exists:areas,id',
        ]);

        if ($request->filled('password')) {
            $validated['password'] = Hash::make($request->password);
        }

        $trainer->update($validated);

        if ($request->hasFile('avatar')) {
            $trainer->clearMediaCollection('avatar');
            $trainer->addMediaFromRequest('avatar')->toMediaCollection('avatar');
        }

        return redirect()->route('web.trainers.index')
            ->with('success', 'Trainer updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $trainer)
    {
        $trainer->delete();

        return redirect()->route('web.trainers.index')
            ->with('success', 'Trainer deleted successfully.');
    }
}
