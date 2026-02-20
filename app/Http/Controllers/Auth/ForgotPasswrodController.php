<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\OtpManagerService;
use Illuminate\Http\Request;

class ForgotPasswrodController extends Controller
{
    private OtpManagerService $otpManagerService;
    public function __construct(OtpManagerService $otpManagerService){
        $this->otpManagerService = $otpManagerService;
    }


    public function forgotPassword(Request $request){
        $request->validate([
            'email' => 'required|email'
        ]);

        $this->otpManagerService->otpEmail($request);

        return response([
            'message' => 'resent otp successfully'
        ],200);

    }
}
