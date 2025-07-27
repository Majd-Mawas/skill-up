<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class PhoneVerificationController extends Controller
{
    use ApiResponse;

    /**
     * Send verification code to user's phone
     */
    public function sendVerificationCode(Request $request)
    {
        $request->validate([
            'phone_number' => ['required', 'string', 'exists:users,phone_number'],
        ]);

        $user = User::where('phone_number', $request->phone_number)->first();

        if ($user->phone_verified) {
            return $this->errorResponse('Phone number is already verified', 400);
        }

        // For testing, we'll use a simple code generation
        // In production, you would use Twilio
        if (env('APP_DEV')) {
            $user->phone_verification_code = '0000';
            $user->phone_verification_code_expires_at = now()->addHours(3);
            $user->save();
        } else {
            $user->sendVerificationCode();
        }

        return $this->successResponse(null, 'Verification code sent successfully');
    }

    /**
     * Verify phone number with code
     */
    public function verifyCode(Request $request)
    {
        $request->validate([
            'phone_number' => ['required', 'string', 'exists:users,phone_number'],
            'code' => ['required', 'string', 'size:4'],
        ]);

        $user = User::where('phone_number', $request->phone_number)->first();

        if ($user->phone_verified) {
            return $this->successResponse(null, 'Phone number is already verified');
        }

        // For testing, accept '0000' as valid code
        if (env('APP_DEV')) {

            if ($request->code === '0000') {
                $user->phone_verified = true;
                $user->phone_verification_code = null;
                $user->phone_verification_code_expires_at = null;
                $user->save();

                return $this->successResponse(null, 'Phone number verified successfully');
            } else {
                return $this->errorResponse('Invalid verification code', 400);
            }
        }

        // Production verification
        if ($user->verifyPhone($request->code)) {
            return $this->successResponse(null, 'Phone number verified successfully');
        }

        return $this->errorResponse('Invalid or expired verification code', 400);
    }
}
