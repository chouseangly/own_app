<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\PaginateRequest;
use App\Http\Requests\ProductBrandRequest;
use App\Http\Resources\ProductBrandResource;
use App\Models\ProductBrand;
use App\Services\ProductBrandService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use PhpParser\Node\Expr\FuncCall;

class ProductBrandController extends AdminController implements HasMiddleware
{
    private ProductBrandService $productBrandService;
    public function __construct(ProductBrandService $productBrandService)
    {
         parent::__construct();
         $this->productBrandService = $productBrandService;
    }
    public static function middleware()
    {
        return [
             new Middleware('permission:settings|products|pos', only: ['index']),
            new Middleware('permission:settings', only: ['store', 'update', 'destroy', 'show']),
        ];
    }

    public function index(PaginateRequest $request){
        try{
            return ProductBrandResource::collection($this->productBrandService->list($request));

        }catch(Exception $e){
            return response(['status'=>false , 'message' => $e->getMessage()],422);
        }
    }

    public function store(ProductBrandRequest $productBrandRequest){
        try{
            return new ProductBrandResource($this->productBrandService->store($productBrandRequest));

        }catch(Exception $e){
            return response(['status'=>false , 'message' => $e->getMessage()],422);
        }
    }

    public function update(ProductBrandRequest $request, ProductBrand $productBrand){
        try{
            return new ProductBrandResource($this->productBrandService->update($request,$productBrand));

        }catch(Exception $e){
            return response(['status'=>false , 'message' => $e->getMessage()],422);
        }
    }

    public function destroy(ProductBrand $productBrand){
        try{
            $this->productBrandService->destroy($productBrand);
            return response('delete brand successfully',202);

        }catch(Exception $e){
            return response(['status'=>false , 'message' => $e->getMessage()],422);
        }
    }

    public function show(ProductBrand $productBrand){
        try{
          return new ProductBrandResource($this->productBrandService->show($productBrand));

        }catch(Exception $e){
            return response(['status'=>false , 'message' => $e->getMessage()],422);
        }
    }
}
