<?php

namespace App\Services;

use App\Http\Requests\PaginateRequest;
use App\Http\Requests\ProductCategoryRequest;
use App\Libraries\QueryExceptionLibrary;
use App\Models\ProductCategory;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

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

     public function ancestorsAndSelf(ProductCategory $productCategory){
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

    public function show(ProductCategory $productCategory){
       try{
         return $productCategory;
       }catch(Exception $e){
         Log::info($e->getMessage());
            throw new Exception(QueryExceptionLibrary::message($e),422);
       }

    }

    public function store(ProductCategoryRequest $request){
        try{
            $categorySlug = Str::slug($request->name);
            $slug = ProductCategory::where('slug',$categorySlug)->first();

            if($slug){
                $categorySlug = Str::slug($request->name) . $request->parent_id;
            }
            $productCategory = ProductCategory::create(Arr::except($request->validated(),'parent_id')
            + ['slug' => $categorySlug , 'parent_id' =>$request->parent_id == 'NULL' ? NULL : $request->parent_id]);
            if($request->image){
                $productCategory->addMediaFromRequest('image')->toMediaCollection('product-category');
            }
            return $productCategory;

        }catch(Exception $e){
         Log::info($e->getMessage());
            throw new Exception(QueryExceptionLibrary::message($e),422);
       }
    }

    public function update(ProductCategoryRequest $request , ProductCategory $productCategory){
        try{
            $categorySlug = Str::slug($request->name);
            $slug = ProductCategory::where('slug',$categorySlug)->first();
            if($slug){
                $categorySlug = Str::slug($request->name) . $request->parent_id;
            }
            $productCategory->update(Arr::except($request->validated(),'parent_id') + ['slug' => $categorySlug , 'parent_id' =>$request->parent_id == 'NULL' ? NULL : $request->parent_id]);
            if($request->image){
                $productCategory->clearMediaCollection('product-category');
                $productCategory->addMediaFromRequest('image')->toMediaCollection('product-category');
            }
            return $productCategory;

        }catch(Exception $e){
         Log::info($e->getMessage());
            throw new Exception(QueryExceptionLibrary::message($e),422);
       }
    }

    public function destroy(ProductCategory $productCategory){
        try{

            $productSubCategory = ProductCategory::find($productCategory->id)->children()->get();
            if(!blank($productSubCategory)){
                throw new Exception('You can not delete this category. because it has sub category.');
            }else{
                $checkProduct = $productCategory->products()->whereNull('deleted_at');
                if(!blank($checkProduct)){
                    $productCategory->delete();
                }else{
                    DB::statement('SET FOREIGN_KEY_CHECKS=0;');
                    $productCategory->delete();
                    DB::statement('SET FOREIGN_KEY_CHECKS=1;');
                }

            }

        }catch(Exception $e){
         Log::info($e->getMessage());
            throw new Exception(QueryExceptionLibrary::message($e),422);
       }
    }
}
