<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\PaginateRequest;
use App\Http\Requests\UnitRequest;
use App\Http\Resources\UnitResource;
use App\Models\Unit;
use App\Services\UnitService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class UnitController extends AdminController implements HasMiddleware
{
    private UnitService $unitService;

    public function __construct(UnitService $unitService)
    {
         parent::__construct();
         $this->unitService = $unitService;
    }
    public static function middleware()
    {
        new Middleware('permission:settings|products',only:['index']);
        new Middleware('permission:settings',only:['index','store','update','distroy']);

    }

    public function index(PaginateRequest $request){
        try{
            return UnitResource::collection($this->unitService->list($request));
        }catch(Exception $e){
            return response(['status' => false, 'message' => $e->getMessage()],422);
        }
    }

    public function store(UnitRequest $unitRequest){
         try{
            return new UnitResource($this->unitService->store($unitRequest));
        }catch(Exception $e){
            return response(['status' => false, 'message' => $e->getMessage()],422);
        }
    }

    public function show(Unit $unit){
         try{
           return new UnitResource($this->unitService->show($unit));
        }catch(Exception $e){
            return response(['status' => false, 'message' => $e->getMessage()],422);
        }
    }

    public function update(UnitRequest $request , Unit $unit){
         try{
            return new UnitResource($this->unitService->update($request,$unit));
        }catch(Exception $e){
            return response(['status' => false, 'message' => $e->getMessage()],422);
        }
    }

    public function destory(Unit $unit){
         try{
            $this->unitService->destory($unit);
            return response('',202);
        }catch(Exception $e){
            return response(['status' => false, 'message' => $e->getMessage()],422);
        }
    }


}
