<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\PaginateRequest;
use App\Http\Resources\ProductBrandResource;
use App\Services\ProductBrandService;
use Exception;
use Illuminate\Http\Request;

class ProductBrandController extends Controller
{
    private ProductBrandService $productBrandService;
    public function __construct(ProductBrandService $productBrandService)
    {
         $this->productBrandService = $productBrandService;
    }

      public function index(PaginateRequest $request){
        try{
            return ProductBrandResource::collection($this->productBrandService->list($request));

        }catch(Exception $e){
            return response(['status'=>false , 'message' => $e->getMessage()],422);
        }
    }
}
