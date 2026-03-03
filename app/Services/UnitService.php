<?php

namespace App\Services;

use App\Http\Requests\PaginateRequest;
use App\Http\Requests\UnitRequest;
use App\Libraries\QueryExceptionLibrary;
use App\Models\Unit;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UnitService{

    public object $unit;
    protected array $unitFilter = [
        'name'
    ];

    public function list(PaginateRequest $request){
        try{
            $requests = $request->all();
            $method = $request->get('paginate',0) == 1 ? 'paginate' : 'get';
            $methodValue = $request->get('paginate',0) == 1 ? $request->get('per_page',10) : '*';
            $orderColumn = $request->get('order_column') ?? 'id';
            $orderType = $request->get('order_type') ?? 'desc';

            return Unit::where(function ($query) use($request, $requests){
                foreach($requests as $key => $request){
                    if(in_array($key, $this->unitFilter)){

                         $query->where($key, 'like' , '%' . $request . '%');
                    }
                }
            })->orderBy($orderColumn,$orderType)->$method(
                $methodValue
            );


        }catch(Exception $e){
            Log::info($e->getMessage());
            throw new Exception(QueryExceptionLibrary::message($e),422);
        }
    }

    public function store(UnitRequest $unitRequest){
        try{
            DB::transaction(function () use ($unitRequest) {
                $this->unit = Unit::create($unitRequest->validated());
            });
            return $this->unit;

        }catch(Exception $e){
             Log::info($e->getMessage());
             DB::rollBack();
            throw new Exception(QueryExceptionLibrary::message($e),422);
        }
    }

    public function show(Unit $unit){
        try{
            return $unit;
        }catch(Exception $e){
             Log::info($e->getMessage());
            throw new Exception(QueryExceptionLibrary::message($e),422);
        }
    }

    public function update(UnitRequest $request , Unit $unit){
        try{

            DB::transaction(function ()  use($request,$unit){
                $unit ->update($request->validated());
            });
            return $unit;
        }catch(Exception $e){
             Log::info($e->getMessage());
             DB::rollBack();
            throw new Exception(QueryExceptionLibrary::message($e),422);
        }
    }

    public function destory(Unit $unit){
        try{
            DB::transaction(function () use ($unit){
                $checkProduct = $unit ->products->whereNull('delete_at');
                if(!blank($checkProduct)){
                    $unit->delete();
                }else {
                    DB::statement('SET FOREIGN_KEY_CHECKS=0');
                    $unit->delete();
                    DB::statement('SET FOREIGN_KEY_CHECKS=1');
                }
            });
        }catch(Exception $e){
             Log::info($e->getMessage());
             DB::rollBack();
            throw new Exception(QueryExceptionLibrary::message($e),422);
        }
    }
}
