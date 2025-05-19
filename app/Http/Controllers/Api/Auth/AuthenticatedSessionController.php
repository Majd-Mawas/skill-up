<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\LoginRequest;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthenticatedSessionController extends Controller
{
    use ApiResponse;

    /**
     * Handle an incoming authentication request.
     *
     * @throws ValidationException
     */
    public function store(LoginRequest $request)
    {
        $request->authenticate();

        $user = Auth::user();
        $token = $user->createToken('auth_token')->plainTextToken;

        return $this->successResponse([
            'user' => $user,
            'access_token' => $token,
            'token_type' => 'Bearer',
        ], 'Login successful');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return $this->successResponse(null, 'Successfully logged out');
    }
}
