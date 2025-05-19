<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class PhoneVerificationController extends Controller
{
    /**
     * Show the phone verification form.
     */
    public function show()
    {
        return view('auth.verify-phone');
    }

    /**
     * Send a verification code to the user's phone number.
     *
     * @throws ValidationException
     */
    public function send(Request $request)
    {
        $request->validate([
            'phone_number' => ['required', 'string', 'regex:/^\+?[1-9]\d{1,14}$/'],
        ]);

        /** @var User $user */
        $user = Auth::user();

        // Update phone number if changed
        if ($user->phone_number !== $request->phone_number) {
            $user->update([
                'phone_number' => $request->phone_number,
                'phone_verified' => false
            ]);
        }

        if ($user->sendVerificationCode()) {
            return back()->with('status', 'Verification code has been sent to your phone number.');
        }

        return back()->with('error', 'Failed to send verification code. Please try again.');
    }

    /**
     * Verify the phone number using the provided code.
     *
     * @throws ValidationException
     */
    public function verify(Request $request)
    {
        $request->validate([
            'verification_code' => ['required', 'string', 'size:6'],
        ]);

        /** @var User $user */
        $user = Auth::user();

        if ($user->verifyPhone($request->verification_code)) {
            return redirect()->route('home')
                ->with('status', 'Phone number verified successfully.');
        }

        return back()->with('error', 'Invalid verification code. Please try again.');
    }
}
