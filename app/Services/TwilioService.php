<?php

namespace App\Services;

use Twilio\Rest\Client;
use Illuminate\Support\Facades\Log;

class TwilioService
{
    protected $client;
    protected $verifyServiceSid;

    public function __construct()
    {
        $this->client = new Client(
            config('services.twilio.sid'),
            config('services.twilio.token')
        );
        $this->verifyServiceSid = config('services.twilio.verify_service_sid');
    }

    public function sendVerification($to)
    {
        try {
            $verification = $this->client->verify->v2
                ->services($this->verifyServiceSid)
                ->verifications
                ->create($to, "sms");

            return [
                'success' => true,
                'status' => $verification->status
            ];
        } catch (\Exception $e) {
            Log::error('Twilio Verification Error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    public function checkVerification($to, $code)
    {
        try {
            $verificationCheck = $this->client->verify->v2
                ->services($this->verifyServiceSid)
                ->verificationChecks
                ->create([
                    'to' => $to,
                    'code' => $code
                ]);

            return [
                'success' => true,
                'status' => $verificationCheck->status
            ];
        } catch (\Exception $e) {
            Log::error('Twilio Verification Check Error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    public function sendPasswordResetCode(string $to): array
    {
        try {
            $verification = $this->client->verify->v2
                ->services($this->verifyServiceSid)
                ->verifications
                ->create($to, 'sms');

            return [
                'success' => true,
                'status' => $verification->status
            ];
        } catch (\Twilio\Exceptions\RestException $e) {
            // Twilio-specific exception gives you $e->getCode()
            Log::error("Twilio Verify error {$e->getCode()}: {$e->getMessage()}");
            return [
                'success' => false,
                'code'    => $e->getCode(),
                'message' => $e->getMessage(),
            ];
        } catch (\Throwable $e) {
            Log::error('Unexpected Twilio error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Unexpected error, please try again later.'
            ];
        }
    }
}
