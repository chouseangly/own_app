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

    public function register(Request $request)
    {
        // It is recommended to add validation here
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        try {
            // Start the transaction
            return DB::transaction(function () use ($request) {

                // 1. Insert user data into users table
                $user = User::create([
                    'name'     => $request->post('name'),
                    'email'    => $request->post('email'),
                    'password' => Hash::make($request->post('password')),
                ]);

                // 2. Insert OTP into otp table and dispatch email event
                // This calls the service you already built
                $this->otpManagerService->otpEmail($request);

                return response([
                    'status' => true,
                    'message' => 'Registration successful. Please check your email for the OTP code.',
                    'user' => $user
                ], 201);
            });

        } catch (Exception $exception) {
            // If anything fails inside the transaction, it rolls back automatically
            return response([
                'status' => false,
                'message' => 'Registration failed: ' . $exception->getMessage()
            ], 422);
        }
    }
}
