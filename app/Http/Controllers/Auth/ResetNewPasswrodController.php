<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ResetNewPasswrodController extends Controller
{
    public function resetNewPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'token' => 'required', // The verified OTP or a unique token
            'password' => 'required|min:8|confirmed',
        ]);

        $user = User::where('email',$request->email)->first();
        $user->update(['password' => Hash::make($request->password)]);
        return response()->json(['message' => 'Password reset successfully.']);
    }
}
