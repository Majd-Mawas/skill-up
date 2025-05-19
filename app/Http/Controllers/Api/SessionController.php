<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\SessionResource;
use App\Models\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SessionController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sessions = Session::with(['course', 'hall', 'trainer'])->paginate(10);
        return $this->sendResponse(SessionResource::collection($sessions), 'Sessions retrieved successfully.');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'course_id' => 'required|exists:courses,id',
            'hall_id' => 'required|exists:halls,id',
            'trainer_id' => 'required|exists:users,id',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'status' => 'required|in:scheduled,in_progress,completed,cancelled',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors()->toArray(), 422);
        }

        $session = Session::create($request->all());
        return $this->sendResponse(new SessionResource($session), 'Session created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Session $session)
    {
        return $this->sendResponse(
            new SessionResource($session->load(['course', 'hall', 'trainer'])),
            'Session retrieved successfully.'
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Session $session)
    {
        $validator = Validator::make($request->all(), [
            'course_id' => 'sometimes|required|exists:courses,id',
            'hall_id' => 'sometimes|required|exists:halls,id',
            'trainer_id' => 'sometimes|required|exists:users,id',
            'start_time' => 'sometimes|required|date',
            'end_time' => 'sometimes|required|date|after:start_time',
            'status' => 'sometimes|required|in:scheduled,in_progress,completed,cancelled',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors()->toArray(), 422);
        }

        $session->update($request->all());
        return $this->sendResponse(new SessionResource($session), 'Session updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Session $session)
    {
        $session->delete();
        return $this->sendResponse(null, 'Session deleted successfully.');
    }
}
