<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Services\TwilioService;
use Illuminate\Support\Facades\Auth;

class PhonePasswordResetController extends Controller
{
    /**
     * Show the phone password reset request form.
     */
    public function showRequestForm()
    {
        return view('auth.passwords.phone-request');
    }

    /**
     * Send a password reset code to the user's phone number.
     *
     * @throws ValidationException
     */
    public function sendResetCode(Request $request)
    {
        $request->validate([
            'phone_number' => ['required', 'string', 'regex:/^\+?[1-9]\d{1,14}$/', 'exists:users,phone_number'],
        ]);

        $user = User::where('phone_number', $request->phone_number)->firstOrFail();

        if ($user->sendPasswordResetCode()) {
            return redirect()->route('password.phone.verify.code')
                ->with('status', 'Password reset code has been sent to your phone number.')
                ->with('phone_number', $request->phone_number);
        }

        return back()->with('error', 'Failed to send reset code. Please try again.');
    }

    /**
     * Show the verification code form.
     */
    public function showVerifyForm()
    {
        return view('auth.passwords.phone-verify');
    }

    /**
     * Verify the password reset code.
     *
     * @throws ValidationException
     */
    public function verifyCode(Request $request)
    {
        $request->validate([
            'phone_number' => ['required', 'regex:/^\+[1-9]\d{1,14}$/'],
            'code' => ['required', 'string', 'size:6'],
        ]);

        $user = User::where('phone_number', $request->phone_number)->first();
        if (!$user) {
            return back()->withErrors(['phone_number' => 'No user found with this phone number.']);
        }

        $twilioService = app(TwilioService::class);
        $result = $twilioService->checkVerification($request->phone_number, $request->code);

        if (!$result['success'] || $result['status'] !== 'approved') {
            return back()->withErrors(['code' => 'Invalid verification code.']);
        }

        // Store phone number and code in session for the next step
        session(['phone_number' => $request->phone_number]);
        session(['code' => $request->code]);

        return redirect()->route('password.phone.reset');
    }

    /**
     * Show the password reset form.
     */
    public function showResetForm()
    {
        return view('auth.passwords.phone-reset');
    }

    /**
     * Reset the user's password.
     *
     * @throws ValidationException
     */
    public function reset(Request $request)
    {
        $request->validate([
            'phone_number' => ['required', 'regex:/^\+[1-9]\d{1,14}$/'],
            'code' => ['required', 'string', 'size:6'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = User::where('phone_number', $request->phone_number)->first();
        if (!$user) {
            return back()->withErrors(['phone_number' => 'No user found with this phone number.']);
        }

        // Verify the code again just to be safe
        // $twilioService = app(TwilioService::class);
        // $result = $twilioService->checkVerification($request->phone_number, $request->code);

        // if (!$result['success'] || $result['status'] !== 'approved') {
        //     return back()->withErrors(['code' => 'Invalid verification code.']);
        // }

        $user->resetPassword($request->password);

        // Log the user in
        Auth::login($user);

        // return redirect()->route('login')
        //     ->with('status', 'Your password has been reset successfully.');
        return redirect()->route('home')
            ->with('status', 'Your password has been reset successfully and you are now logged in.');
    }
}
