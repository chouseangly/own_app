<?php

namespace App\Services;

use App\Http\Requests\ChangeImageRequest;
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\ProfileRequest;
use App\Libraries\QueryExceptionLibrary;
use App\Models\User;
use Exception;
use GuzzleHttp\Psr7\Query;
use Illuminate\Support\Facades\Log;
use PhpParser\Node\Expr\FuncCall;

class ProfileService{

    public function update(ProfileRequest $request){
        try{
            $user = User::find(auth()->user()->id);
            if(!blank($user)){
                $user->name = $request->name;
                $user->phone = $request->get('phone');
                $user->email = $request->get('email');
                $user->country_code = $request->get('country_code');
                $user->save();
            }
            if($request->image){
                $user->clearMediaCollection('profile');
                $user->addMediaFromRequest('image')->toMediaCollection('profile');
            }
            return $user;
        }catch(Exception $e){
            Log::info($e->getMessage());
            throw new Exception(QueryExceptionLibrary::message($e),422);
        }
    }

    public function changePassword(ChangePasswordRequest $request){
        try{
            $user = User::find(auth()->user()->id);
            $user->password = bcrypt($request->get('new_password'));
            $user->save();
            return $user;
        }catch(Exception $e){
            Log::info($e->getMessage());
            throw new Exception(QueryExceptionLibrary::message($e),422);
        }
    }

    public function changeImage(ChangeImageRequest $request){
        try{
            $user = User::find(auth()->user()->id);
            if($request->image){
                $user->clearMediaCollection('profile');
                $user->addMediaFromRequest('image')->toMediaCollection('profile');

            }
            $user->save();
            return $user;
        }catch(Exception $e){
            Log::info($e->getMessage());
        throw new Exception(QueryExceptionLibrary::message($e),422);
        }
    }


}
