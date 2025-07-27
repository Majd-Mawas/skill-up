<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class PasswordResetController extends Controller
{
    use ApiResponse;

    /**
     * Request password reset code
     */
    public function requestResetCode(Request $request)
    {
        $request->validate([
            'phone_number' => ['required', 'string', 'exists:users,phone_number'],
        ]);

        $user = User::where('phone_number', $request->phone_number)->first();

        // For testing, we'll use a simple code generation
        if (env('APP_DEV')) {
            $user->password_reset_code = '0000';
            $user->password_reset_code_expires_at = now()->addHours(3);
            $user->save();
        } else {
            $user->sendPasswordResetCode();
        }

        return $this->successResponse(null, 'Password reset code sent successfully');
    }

    /**
     * Verify reset code
     */
    public function verifyResetCode(Request $request)
    {
        $request->validate([
            'phone_number' => ['required', 'string', 'exists:users,phone_number'],
            'code' => ['required', 'string', 'size:4'],
        ]);

        $user = User::where('phone_number', $request->phone_number)->first();

        // For testing, accept '0000' as valid code
        if (env('APP_DEV')) {
            if ($request->code === '0000') {
                return $this->successResponse([
                    'reset_token' => base64_encode($user->phone_number . '|' . now()->addMinutes(15)->timestamp)
                ], 'Reset code verified successfully');
            } else {
                return $this->errorResponse('Invalid reset code', 400);
            }
        }

        // Production verification using Twilio
        if ($user->verifyPasswordResetCode($request->code)) {
            return $this->successResponse([
                'reset_token' => base64_encode($user->phone_number . '|' . now()->addMinutes(15)->timestamp)
            ], 'Reset code verified successfully');
        }

        return $this->errorResponse('Invalid or expired reset code', 400);
    }

    /**
     * Reset password
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'reset_token' => ['nullable', 'string'],
            'password' => ['required', 'confirmed', Rules\Password::min(8)->letters()->mixedCase()->numbers()->symbols()],
        ]);

        // Decode and validate reset token
        try {
            $decoded = base64_decode($request->reset_token);
            [$phone_number, $expires_at] = explode('|', $decoded);

            // if (now()->timestamp > $expires_at) {
            //     return $this->errorResponse('Reset token has expired', 400);
            // }

            $user = User::where('phone_number', $phone_number)->first();

            if (!$user) {
                return $this->errorResponse('Invalid reset token', 400);
            }

            $user->password = Hash::make($request->password);
            $user->password_reset_code = null;
            $user->password_reset_code_expires_at = null;
            $user->save();

            // Revoke all existing tokens
            $user->tokens()->delete();

            // Create new token for user
            $token = $user->createToken('auth_token')->plainTextToken;

            return $this->successResponse([
                'user' => $user,
                'access_token' => $token,
                'token_type' => 'Bearer',
            ], 'Password reset successfully');
        } catch (\Exception $e) {
            return $this->errorResponse('Invalid reset token', 400);
        }
    }
}
