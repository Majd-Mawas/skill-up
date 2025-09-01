<?php

namespace App\Http\Controllers;

use App\Models\Hall;
use App\Models\HallBooking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class HallBookingController extends Controller
{
    /**
     * Display a listing of the hall bookings.
     */
    public function index()
    {
        $hallBookings = HallBooking::with(['hall', 'user'])->latest()->paginate(10);
        return view('hall-bookings.index', compact('hallBookings'));
    }

    /**
     * Show the form for creating a new hall booking.
     */
    public function create()
    {
        $halls = Hall::where('available', true)->get();
        return view('hall-bookings.create', compact('halls'));
    }

    /**
     * Store a newly created hall booking in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'hall_id' => 'required|exists:halls,id',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Calculate total price
        $hall = Hall::findOrFail($request->hall_id);
        $startDate = new \DateTime($request->start_date);
        $endDate = new \DateTime($request->end_date);
        $startTime = new \DateTime($request->start_time);
        $endTime = new \DateTime($request->end_time);
        
        $days = $startDate->diff($endDate)->days + 1;
        $hours = $startTime->diff($endTime)->h;
        $totalPrice = $hall->price_per_hour * $hours * $days;

        // Check for availability
        $conflictingBookings = HallBooking::where('hall_id', $request->hall_id)
            ->where('status', '!=', 'cancelled')
            ->where(function ($query) use ($request) {
                $query->whereBetween('start_date', [$request->start_date, $request->end_date])
                    ->orWhereBetween('end_date', [$request->start_date, $request->end_date])
                    ->orWhere(function ($query) use ($request) {
                        $query->where('start_date', '<=', $request->start_date)
                            ->where('end_date', '>=', $request->end_date);
                    });
            })
            ->exists();

        if ($conflictingBookings) {
            return redirect()->back()
                ->withErrors(['availability' => 'The hall is not available for the selected dates and times.'])
                ->withInput();
        }

        // Create booking
        $hallBooking = new HallBooking([
            'hall_id' => $request->hall_id,
            'user_id' => Auth::id(),
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'total_price' => $totalPrice,
            'status' => 'pending',
        ]);

        $hallBooking->save();

        return redirect()->route('hall-bookings.show', $hallBooking->id)
            ->with('success', 'Hall booking created successfully.');
    }

    /**
     * Display the specified hall booking.
     */
    public function show(HallBooking $hallBooking)
    {
        $hallBooking->load(['hall', 'user']);
        return view('hall-bookings.show', compact('hallBooking'));
    }

    /**
     * Show the form for editing the specified hall booking.
     */
    public function edit(HallBooking $hallBooking)
    {
        $halls = Hall::where('available', true)->get();
        return view('hall-bookings.edit', compact('hallBooking', 'halls'));
    }

    /**
     * Update the specified hall booking in storage.
     */
    public function update(Request $request, HallBooking $hallBooking)
    {
        $validator = Validator::make($request->all(), [
            'hall_id' => 'required|exists:halls,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'status' => 'required|in:pending,confirmed,cancelled',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Calculate total price
        $hall = Hall::findOrFail($request->hall_id);
        $startDate = new \DateTime($request->start_date);
        $endDate = new \DateTime($request->end_date);
        $startTime = new \DateTime($request->start_time);
        $endTime = new \DateTime($request->end_time);
        
        $days = $startDate->diff($endDate)->days + 1;
        $hours = $startTime->diff($endTime)->h;
        $totalPrice = $hall->price_per_hour * $hours * $days;

        // Check for availability (excluding this booking)
        $conflictingBookings = HallBooking::where('hall_id', $request->hall_id)
            ->where('id', '!=', $hallBooking->id)
            ->where('status', '!=', 'cancelled')
            ->where(function ($query) use ($request) {
                $query->whereBetween('start_date', [$request->start_date, $request->end_date])
                    ->orWhereBetween('end_date', [$request->start_date, $request->end_date])
                    ->orWhere(function ($query) use ($request) {
                        $query->where('start_date', '<=', $request->start_date)
                            ->where('end_date', '>=', $request->end_date);
                    });
            })
            ->exists();

        if ($conflictingBookings) {
            return redirect()->back()
                ->withErrors(['availability' => 'The hall is not available for the selected dates and times.'])
                ->withInput();
        }

        // Update booking
        $hallBooking->update([
            'hall_id' => $request->hall_id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'total_price' => $totalPrice,
            'status' => $request->status,
        ]);

        return redirect()->route('hall-bookings.show', $hallBooking->id)
            ->with('success', 'Hall booking updated successfully.');
    }

    /**
     * Remove the specified hall booking from storage.
     */
    public function destroy(HallBooking $hallBooking)
    {
        $hallBooking->delete();

        return redirect()->route('hall-bookings.index')
            ->with('success', 'Hall booking deleted successfully.');
    }

    /**
     * Cancel the specified hall booking.
     */
    public function cancel(HallBooking $hallBooking)
    {
        $hallBooking->update(['status' => 'cancelled']);

        return redirect()->route('hall-bookings.show', $hallBooking->id)
            ->with('success', 'Hall booking cancelled successfully.');
    }

    /**
     * Confirm the specified hall booking.
     */
    public function confirm(HallBooking $hallBooking)
    {
        $hallBooking->update(['status' => 'confirmed']);

        return redirect()->route('hall-bookings.show', $hallBooking->id)
            ->with('success', 'Hall booking confirmed successfully.');
    }
}