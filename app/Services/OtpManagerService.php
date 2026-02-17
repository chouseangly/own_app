<?php

// app/Services/OtpManagerService.php
namespace App\Services;
use App\Events\SendEmailCode;
use App\Models\Otp;
use Exception;
use Illuminate\Http\Request;

class OtpManagerService {
    public function otpEmail(Request $request): bool
    {
        try {
            // 1. Delete existing OTPs for this email to start fresh
            Otp::where('email', $request->post('email'))->delete();

            // 2. Generate a random token (e.g., 6 digits)
            $token = rand(100000, 999999);

            // 3. Store the new OTP
            Otp::create([
                'email'      => $request->post('email'),
                'token'      => $token,
                'created_at' => now(),
            ]);

            // 4. Dispatch the event to send the mail
            SendEmailCode::dispatch([
                'email' => $request->post('email'),
                'token' => $token
            ]);

            return true;
        } catch (Exception $exception) {
            \Illuminate\Support\Facades\Log::error($exception->getMessage());
            throw new Exception("Failed to send OTP", 422);
        }
    }
}
