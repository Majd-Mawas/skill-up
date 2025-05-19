<?php

namespace App\Traits;

use App\Services\TwilioService;
use Illuminate\Support\Str;
use Carbon\Carbon;

trait HasPhoneVerification
{
    public function generateVerificationCode()
    {
        $code = Str::random(6);
        $this->phone_verification_code = $code;
        $this->phone_verification_code_expires_at = Carbon::now()->addHours(3);
        $this->save();
        return $code;
    }

    public function generatePasswordResetCode()
    {
        $code = Str::random(6);
        $this->password_reset_code = $code;
        $this->password_reset_code_expires_at = Carbon::now()->addHours(3);
        $this->save();
        return $code;
    }

    public function sendVerificationCode()
    {
        if (!$this->phone_number) {
            return false;
        }

        $code = $this->generateVerificationCode();
        return app(TwilioService::class)->sendVerificationCode($this->phone_number, $code);
    }

    public function sendPasswordResetCode()
    {
        if (!$this->phone_number) {
            return false;
        }

        return app(TwilioService::class)->sendPasswordResetCode($this->phone_number);
    }

    public function verifyPhone($code)
    {
        if ($this->phone_verified) {
            return true;
        }

        if (!$this->phone_verification_code || !$this->phone_verification_code_expires_at) {
            return false;
        }

        if (Carbon::now()->isAfter($this->phone_verification_code_expires_at)) {
            return false;
        }

        if ($this->phone_verification_code !== $code) {
            return false;
        }

        $this->phone_verified = true;
        $this->phone_verification_code = null;
        $this->phone_verification_code_expires_at = null;
        $this->save();

        return true;
    }

    public function verifyPasswordResetCode($code)
    {
        if (!$this->phone_number) {
            return false;
        }

        $result = app(TwilioService::class)->checkVerification($this->phone_number, $code);
        return $result['success'] && $result['status'] === 'approved';
    }

    public function resetPassword($newPassword)
    {
        $this->password = bcrypt($newPassword);
        $this->save();
        return true;
    }
}
