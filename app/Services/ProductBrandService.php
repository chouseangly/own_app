<?php

namespace App\Services;

use App\Http\Requests\PaginateRequest;
use App\Http\Requests\ProductBrandRequest;
use App\Models\ProductBrand;
use Exception;
use App\Libraries\QueryExceptionLibrary;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use PhpParser\Node\Expr\FuncCall;

class ProductBrandService{

    protected $ProductBrandFilter = [
        'name',
        'slug',
        'description',
        'status'
    ];

    protected $exceptFilter = [
        'excepts'
    ];

    public function list(PaginateRequest $request)
    {
        try {
            $requests = $request->all();
            $method = $request->get('paginate', 0) == 1 ? 'paginate' : 'get';
            $methodValue = $request->get('paginate', 0) == 1 ? $request->get('per_page', 10) : '*';
            $orderColumn = $request->get('order_column') ?? 'id';
            $orderType = $request->get('order_type') ?? 'desc';

            return ProductBrand::with('media')->where(function ($query) use ($request, $requests) {
                foreach ($requests as $key => $request) {
                    if (in_array($key, $this->ProductBrandFilter)) {
                        $query->where($key, 'like', '%' . $request . '%');
                    }

                    if (in_array($key, $this->exceptFilter)) {
                        $explodes = explode('|', $request);
                        if (is_array($explodes)) {
                            foreach ($explodes as $explode) {
                                $query->where('id', '!=', $explode);
                            }
                        }
                    }
                }
            })->orderBy($orderColumn, $orderType)->$method(
                $methodValue
            );
        } catch (Exception $e) {
            Log::info($e->getMessage());
            return new Exception(QueryExceptionLibrary::message($e), 422);
        }
    }

    public function store(ProductBrandRequest $request){
        try{
            $productBrand = ProductBrand::create($request->validated() + ['slug' => Str::slug($request->slug)]);
            if($request->image){
                $productBrand->addMediaFromRequest('image')
                ->toMediaCollection('product_brand');
            }
            return $productBrand;
        }catch (Exception $e) {
            Log::info($e->getMessage());
            return new Exception(QueryExceptionLibrary::message($e), 422);
        }
    }

    public function update(ProductBrandRequest $request, ProductBrand $productBrand){
        try{
            $productBrand->update($request->validated() + ['slug' => Str::slug($request->slug)]);
            if($request->image){
                $productBrand->clearMediaCollection('product_brand');
                $productBrand->addMediaFromRequest('image')->toMediaCollection('product_brand');
            }
            return $productBrand;
        }catch (Exception $e) {
            Log::info($e->getMessage());
            return new Exception(QueryExceptionLibrary::message($e), 422);
        }
    }

    public function destroy(ProductBrand $productBrand){
        try{
            $productBrand->delete();
        }catch (Exception $e) {
            Log::info($e->getMessage());
            return new Exception(QueryExceptionLibrary::message($e), 422);
        }
    }

    public function show(ProductBrand $productBrand){
        try{
            return $productBrand;
        }catch (Exception $e) {
            Log::info($e->getMessage());
            return new Exception(QueryExceptionLibrary::message($e), 422);
        }
    }
}
