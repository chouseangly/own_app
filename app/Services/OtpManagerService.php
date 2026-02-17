<?php

namespace App\Services;

class OtpManagerService {
    // chouseangly/own_app/own_app-main/app/Services/OtpManagerService.php

public function otpEmail(Request $request): bool
{
    try {
        // Handle demo mode logic if applicable
        if (env('DEMO') == "True" || env('DEMO') == "TRUE" || env('DEMO') == "true" || env('DEMO') == 1) {
            return true;
        }

        // Check if an OTP already exists for this email and delete it to start fresh
        $otp = Otp::where('email', $request->post('email'));
        if ($otp->exists()) {
            $otp->delete();
        }

        // Determine OTP digit limit from settings
        $otpDigitLimit = (int) Settings::group('otp')->get('otp_digit_limit');
        $token = rand(pow(10, $otpDigitLimit - 1), pow(10, $otpDigitLimit) - 1);

        // Store the new OTP in the database
        $otp = Otp::create([
            'email'      => $request->post('email'),
            'token'      => $token,
            'created_at' => now(),
        ]);

        // Dispatch the email event
        if (!blank($otp)) {
            \App\Events\SendEmailCode::dispatch([
                'email' => $request->post('email'),
                'token' => $token
            ]);
        }

        return true;
    } catch (Exception $exception) {
        \Illuminate\Support\Facades\Log::info($exception->getMessage());
        throw new Exception(\App\Libraries\QueryExceptionLibrary::message($exception), 422);
    }
}
}
