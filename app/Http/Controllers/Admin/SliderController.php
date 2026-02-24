<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\PaginateRequest;
use App\Http\Requests\SliderRequest;
use App\Http\Resources\SliderResource;
use App\Models\Slider;
use App\Services\SliderService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class SliderController extends AdminController implements HasMiddleware
{
    private SliderService $sliderService;

    public function __construct(SliderService $sliderService)
    {
        parent::__construct();
        $this->sliderService = $sliderService;
    }
    public static function middleware()
    {
        return [
            new Middleware('permission:settings',only:['index','store','update','destroy','show']),
        ];
    }


    public function index(PaginateRequest $request){
        try{

            return SliderResource::collection($this->sliderService->list($request));
        }catch(Exception $e){
            return response(['status' => false, 'message' => $e->getMessage()],422);
        }
    }

    public function store(SliderRequest $request){
        try{
            return new SliderResource($this->sliderService->store($request));
        }catch(Exception $e){
            return response(['status' => false, 'message' => $e->getMessage()],422);
        }
    }

    public function show(Slider $slider){
         try{
            return new SliderResource($this->sliderService->show($slider));
        }catch(Exception $e){
            return response(['status' => false, 'message' => $e->getMessage()],422);
        }
    }

    public function update(SliderRequest $request , Slider $slider){
         try{
            return new SliderResource($this->sliderService->update($request,$slider));
        }catch(Exception $e){
            return response(['status' => false, 'message' => $e->getMessage()],422);
        }
    }

    public function destory(Slider $slider){
         try{
            $this->sliderService->destroy($slider);
            return response('',202);
        }catch(Exception $e){
            return response(['status' => false, 'message' => $e->getMessage()],422);
        }
    }
}
