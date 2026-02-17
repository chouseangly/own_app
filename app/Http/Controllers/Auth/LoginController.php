<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller{
    public function login(Request $request){
        $request->validate([
            'email' => 'required|string|email|exists:users,email',
            'password' => 'required|string'
        ]);

        $user = User::where('email',$request->email)->first();

        if(!$user || !Hash::check($request->password , $user->password)){
            return response(['status' => false, 'message' => 'Invalid Credentials'],401);
        }

        if(!$user->email_verified_at){
            return response(['status' => false , 'message' => 'Please verify your email'],403);
        }

        // Create Sanctum Token

        $token = $user->createToken('auth_token')->plainTextToken;

        return response([
            'status' => true,
            'token' => $token,
            'user' => $user,
            'message' => 'Login successfully'

        ]);
    }
}
