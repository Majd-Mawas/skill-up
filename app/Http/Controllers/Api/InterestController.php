<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Interest;

class InterestController extends Controller
{
    /**
     * Display a listing of active interests.
     */
    public function index()
    {
        $interests = Interest::all();

        return $this->successResponse($interests, 'Interests retrieved successfully');
    }

    /**
     * Display the specified interest.
     */
    public function show(Interest $interest)
    {
        return $this->successResponse($interest, 'Interest retrieved successfully');
    }
}
