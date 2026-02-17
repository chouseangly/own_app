<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\OtpManagerService;
use Illuminate\Http\Request;
use Exception;

class SignupController extends Controller
{
    private OtpManagerService $otpManagerService;
    public function __construct(OtpManagerService $otpManagerService)
    {
        $this->otpManagerService = $otpManagerService;
    }

   // chouseangly/own_app/own_app-main/app/Http/Controllers/Auth/SignupController.php

public function otpEmail(Request $request): \Illuminate\Http\Response|\Illuminate\Contracts\Routing\ResponseFactory
{
    try {
       

        $this->otpManagerService->otpEmail($request);

        return response([
            'status' => true,
            'message' => trans("all.message.check_your_email_for_code")
        ]);
    } catch (Exception $exception) {
        return response([
            'status' => false,
            'message' => $exception->getMessage()
        ], 422);
    }
}
}
