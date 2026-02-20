<?php

namespace App\Services;

use App\Http\Requests\PaginateRequest;
use App\Libraries\QueryExceptionLibrary;
use App\Models\ProductCategory;
use Exception;
use GuzzleHttp\Psr7\Query;
use Illuminate\Support\Facades\Log;

class ProductCategoryService{

    protected array $productCategoryFilter = [
        'name',
        'slug',
        'description',
        'status',
        'parent_id'
    ];

    protected array $exceptFilter = [
        'excepts'
    ];

     /**
     * @throws Exception
     */

     public function ancestorAndSelf(ProductCategory $productCategory){
       try{
         return $productCategory->ancestorsAndSelf->reverse();
       }catch(Exception $e){
        Log::info($e->getMessage());
        throw new Exception(QueryExceptionLibrary::message($e),422);
       }
     }

     public function depthTree(){
        try{
            return ProductCategory::tree()->depthFirst()->get();
        }catch(Exception $e){
            Log::info($e->getMessage());
            throw new Exception(QueryExceptionLibrary::message($e),422);
        }
     }

     /**
     * @throws Exception
     */
    public function tree()
    {
        try {
            return ProductCategory::active()->tree()->get();
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception(QueryExceptionLibrary::message($exception), 422);
        }
    }

     /**
     * @throws Exception
     */

    public function list(PaginateRequest $request){
        try{
            $requests = $request->all();
            $method = $request->get('paginate',0) == 1 ? 'paginate' : 'get';
            $methodValue = $request->get('paginate',0) == 1 ? $request->get('per_page',10) : '*';
            $orderColumn = $request->get('order_column') ?? 'id';
            $orderType = $request->get('order_type') ?? 'desc';

            return ProductCategory::tree()->depthFirst()->with('parent_category','media','products')->where(function($query) use ($request, $requests){
                foreach($requests as $key =>$request){
                    if(in_array($key, $this->productCategoryFilter)){

                        $query->where($key, 'like' , '%' . $request . '%');
                    }

                    if(in_array($key,$this->exceptFilter)){
                        $explodes = explode('|',$request);
                        if(is_array($explodes)){
                            foreach($explodes as $key => $explode){
                                $query->where('id', '!=',$explode);
                            }
                        }
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
