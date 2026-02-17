<?php



namespace App\Services;

use App\Enums\Ask;
use Exception;
use Carbon\Carbon;
use App\Models\Otp;
use App\Enums\OtpType;
use App\Events\SendSmsCode;
use Illuminate\Http\Request;
use App\Events\SendEmailCode;
use Illuminate\Support\Facades\DB;
use App\Events\SendVerifyEmailCode;
use Illuminate\Support\Facades\Log;
use Dipokhalder\Settings\Facades\Settings;
use App\Http\Requests\VerifyEmailRequest;
use App\Http\Requests\VerifyPhoneRequest;
use App\Libraries\QueryExceptionLibrary;

class OtpManagerService
{
    // This service will handle OTP generation, validation, and management.
    // It will interact with the Opt model to store and retrieve OTP records.

    public function otpPhone(Request $request): bool
    {
        try {
            if (env('DEMO') == "True" || env('DEMO') == "TRUE" || env('DEMO') == "true" || env('DEMO') == 1) {
                return true;
            }
            $otp = DB::table('otps')->where([
                ['phone', $request->post('phone')],
                ['code', $request->post('country_code')],
            ]);

            if ($otp->exists()) {
                $otp->delete();
            }

            if (
                OtpType::SMS == Settings::group('otp')->get('otp_type') || OtpType::BOTH == Settings::group('otp')->get(
                    'otp_type'
                )
            ) {
                $token = rand(
                    pow(10, (int) Settings::group('otp')->get('otp_digit_limit') - 1),
                    pow(10, (int) Settings::group('otp')->get('otp_digit_limit')) - 1
                );
            } else {
                $token = rand(pow(10, 4 - 1), pow(10, 4) - 1);
            }

            $otp = Otp::create([
                'phone' => $request->phone,
                'code' => $request->country_code,
                'token' => $token,
                'created_at' => now(),
            ]);

            if (!blank($otp)) {
                SendSmsCode::dispatch(
                    ['phone' => $request->post('phone'), 'code' => $request->post('country_code'), 'token' => $token]
                );
            }

            return true;
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception(QueryExceptionLibrary::message($exception), 422);
        }
    }
}
