<?php

namespace App\Services;

use App\Http\Requests\PaginateRequest;
use App\Libraries\QueryExceptionLibrary;
use App\Models\Barcode;
use Exception;
use Illuminate\Support\Facades\Log;

class BarcodeService{

    protected $barcodeFilter = [
        'name'
    ];

    public function list(PaginateRequest $request){
        try{
            $requests = $request->all();
            $method = $request->get('paginate',0) == 1 ? 'paginate' : 'get';
            $methodValue = $request->get('paginate',0) == 1 ? $request->get('per_page',10) : '*';
            $orderColumn = $request->get('order_column') ?? 'id';
            $orderType = $request->get('order_type') ?? 'desc';

            return Barcode::where(function ($query) use ($request, $requests){
                foreach($requests as $key => $request){
                    if(in_array($key , $this->barcodeFilter)){
                        $query->where($key , 'like' ,'%' . $request . '%' );
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
}
