<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\PaginateRequest;
use App\Http\Resources\ProductCategoryResource;
use App\Models\ProductCategory;
use App\Services\ProductCategoryService;
use Exception;
use Illuminate\Http\Request;

class ProductCategoryController extends Controller
{
    private ProductCategoryService $productCategoryService;

    public function __construct(ProductCategoryService $productCategoryService)
    {
        $this->productCategoryService = $productCategoryService;
    }

    public function ancestorAndSelf(ProductCategory $productCategory){
        try{
            return ProductCategoryResource::collection($this->productCategoryService->ancestorAndSelf($productCategory));
        }catch(Exception $exception){
            return response(['status' => false, 'message' => $exception->getMessage()]);
        }
    }

    public function tree(){
        try{
            return ProductCategoryResource::collection($this->productCategoryService->tree());
        }catch(Exception $e){
            return response(['status' => false, 'message' => $e->getMessage()]);
        }
    }

    public function index(PaginateRequest $request){
        try{
            return ProductCategoryResource::collection($this->productCategoryService->list($request));
        }catch(Exception $e){
            return response(['status' => false , 'message' => $e->getMessage()]);
        }
    }
}
