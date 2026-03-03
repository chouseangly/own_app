<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\ChangeImageRequest;
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\ProfileRequest;
use App\Http\Resources\UserResource;
use App\Services\ProfileService;
use Exception;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    private ProfileService $profileService;

    public function __construct(ProfileService $profileService)
    {
        $this->profileService = $profileService;
    }

    public function profile(Request $request){
        try{
            return new UserResource(auth()->user());
        }catch(Exception $e){
            return response(['status' => false , 'message' => $e->getMessage()],422);
        }
    }

    public function update(ProfileRequest $request){
        try{
            return new UserResource($this->profileService->update($request));
        }catch(Exception $e){
            return response(['status' => false , 'message' => $e->getMessage()],422);
        }
    }

    public function changePassword(ChangePasswordRequest $request){
        try{
            return new UserResource($this->profileService->changePassword($request));
        }catch(Exception $e){
            return response(['status' => false , 'message' => $e->getMessage()],422);
        }
    }

    public function changeImage(ChangeImageRequest $request){
        try{
            return new UserResource($this->profileService->changeImage($request));
        }catch(Exception $e){
            return response(['status' => false , 'message' => $e->getMessage()],422);
        }
    }
}
