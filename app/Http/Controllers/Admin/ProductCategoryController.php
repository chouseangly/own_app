<?php

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Admin\AdminController;
use App\Http\Requests\PaginateRequest;
use App\Http\Requests\ProductCategoryRequest;
use App\Http\Resources\ProductCategoryDepthTreeResource;
use App\Http\Resources\ProductCategoryResource;
use App\Models\ProductCategory;
use App\Services\ProductCategoryService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Pest\Plugins\Only;

class ProductCategoryController extends AdminController implements HasMiddleware
{
    private ProductCategoryService $productCategoryService;

    public function __construct(ProductCategoryService $productCategoryService)
    {
        return parent::__construct();

        $this->productCategoryService = $productCategoryService;
    }

    public static function middleware()
    {
        return [
            new Middleware('permission:settings', only: ['index', 'store', 'update', 'destroy', 'show', 'export', 'downloadAttechment', 'import']),
        ];
    }

    public function depthTree()
    {
        try {
            return ProductCategoryDepthTreeResource::collection($this->productCategoryService->depthTree());
        } catch (Exception $e) {
            return response(['status' => false, 'message' => $e->getMessage()]);
        }
    }

    public function index(PaginateRequest $request){
        try{
            return ProductCategoryResource::collection($this->productCategoryService->list($request));
        }catch(Exception $e){
            return response(['status' => false, 'message' => $e->getMessage()],422);
        }
    }

    public function store(ProductCategoryRequest $request){
        try{
            return new ProductCategoryResource($this->productCategoryService->store($request));
        }catch(Exception $e){
            return response(['status' => false, 'message' => $e->getMessage()],422);
        }
    }

    public function update(ProductCategoryRequest $request,ProductCategory $productCategory){
        try{
            return new ProductCategoryResource($this->productCategoryService->update($request,$productCategory));
        }catch(Exception $e){
            return response(['status' => false, 'message' => $e->getMessage()],422);
        }
    }

    public function destroy(ProductCategory $productCategory){
        try{
            $this->productCategoryService->destroy($productCategory);
        return response('',202);
        }catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function ancestorsAndSelf(ProductCategory $productCategory): \Illuminate\Foundation\Application|\Illuminate\Http\Response|\Illuminate\Http\Resources\Json\AnonymousResourceCollection|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            return ProductCategoryResource::collection($this->productCategoryService->ancestorsAndSelf($productCategory));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function tree()
    {
        try {
            return $this->productCategoryService->tree()->toTree();
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }
}
