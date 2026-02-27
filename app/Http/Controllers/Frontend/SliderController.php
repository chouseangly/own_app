<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\PaginateRequest;
use App\Http\Resources\SliderResource;
use App\Services\SliderService;
use Exception;
use Illuminate\Http\Request;

class SliderController extends Controller
{
    private SliderService $sliderService;

    public function __construct(SliderService $sliderService)
    {
        $this->sliderService = $sliderService;
    }

    public function index(PaginateRequest $request){
        try{
            return SliderResource::collection($this->sliderService->list($request));
        }catch(Exception $e){
            return response(['status' => false, 'message' => $e->getMessage()]);
        }
    }


}
