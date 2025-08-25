<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Interest;
use App\Traits\ApiResponse;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;

class RegisteredUserController extends Controller
{


    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'gender' => ['required', 'string', 'in:male,female'],
            'study' => ['required', 'string', 'max:255'],
            'phone_number' => ['required', 'string'],
            'password' => ['required', 'confirmed', Rules\Password::min(8)->letters()->mixedCase()->numbers()->symbols()],
            'interests' => ['required', 'array', 'min:1'],
            'interests.*' => ['required', 'integer', 'exists:interests,id'],
        ]);

        $interests = Interest::whereIn('id', $request->interests)->active()->get();

        if ($interests->count() !== count($request->interests)) {
            return $this->errorResponse('One or more selected interests are invalid or inactive', 400);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'gender' => $request->gender,
            'study' => $request->study,
            'phone_number' => $request->phone_number,
            'password' => Hash::make($request->password),
            'phone_verified' => false,
        ]);

        $user->interests()->attach($request->interests);

        event(new Registered($user));
        if (env('APP_DEV')) {
            $user->phone_verification_code = '0000';
            $user->phone_verification_code_expires_at = now()->addHours(3);
            $user->save();
        } else {
            $user->sendVerificationCode();
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        $user->load('interests');

        return $this->successResponse([
            'user' => $user,
            'access_token' => $token,
            'token_type' => 'Bearer',
            'message' => 'Registration successful. Please verify your phone number.',
        ], 'User registered successfully', 201);
    }
}
