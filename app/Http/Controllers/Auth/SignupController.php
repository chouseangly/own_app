<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\OtpManagerService;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB; // Import DB facade
use Exception;

use function Symfony\Component\Clock\now;

class SignupController extends Controller
{
    private OtpManagerService $otpManagerService;

    public function __construct(OtpManagerService $otpManagerService)
    {
        $this->otpManagerService = $otpManagerService;
    }

    // app/Http/Controllers/Auth/SignupController.php

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        try {
            // Create the user first (outside a global transaction if you want them saved regardless of mail)
            $user = User::create([
                'name'     => $request->post('name'),
                'email'    => $request->post('email'),
                'password' => Hash::make($request->post('password')),
            ]);

            // Attempt to send OTP
            try {
                $this->otpManagerService->otpEmail($request);
            } catch (Exception $e) {
                // Log the error but don't stop the registration
                \Illuminate\Support\Facades\Log::error("OTP failed: " . $e->getMessage());
                return response([
                    'status' => true,
                    'message' => 'Error: ' . $e->getMessage(), // Change this to show the real error
                    'user' => $user
                ], 201);
            }

            return response([
                'status' => true,
                'message' => 'Registration successful. Check your email.',
                'user' => $user
            ], 201);
        } catch (Exception $exception) {
            return response([
                'status' => false,
                'message' => 'Registration failed: ' . $exception->getMessage()
            ], 422);
        }
    }

    public function verifyOtp(Request $request)
    {

        $request->validate([
            'email' => 'required|email|exists:users,email',
            'token' => 'required|string',
        ]);

        $isValid = $this->otpManagerService->verifyOtp($request->email, $request->token);


        if (!$isValid) {
            return response(['status' => false, 'message' => 'Invalid or expired otp'], 422);
        }

        // Mark user as verified

        $user = User::where('email', $request->email)->first();
        $user->email_verified_at = now();
        $user->save();

        return response(['status' => true, 'message' => 'Otp verified successfully'], 200);
    }

    public function resentOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email'
        ]);

        // sent otp code
        $this->otpManagerService->otpEmail($request);

        return response()->json([
            'status' => 'success',
            'message' => 'OTP resent successfully.'
        ], 200);
    }
}
