<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\OtpManagerService;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB; // Import DB facade
use Exception;

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
                'message' => 'User created, but failed to send OTP. Please try resending OTP.',
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
}
