<?php

namespace App\Http\Controllers\Admin;


use App\Exports\ProductCategoryExport;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Requests\PaginateRequest;
use App\Http\Requests\ProductCategoryRequest;
use App\Http\Resources\ProductCategoryDepthTreeResource;
use App\Http\Resources\ProductCategoryResource;
use App\Imports\ProductCategoryImport;
use App\Models\ProductCategory;
use App\Services\ProductCategoryService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class ProductCategoryController extends AdminController implements HasMiddleware
{
    private ProductCategoryService $productCategoryService;

    public function __construct(ProductCategoryService $productCategoryService)
    {
         parent::__construct();
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

    public function show(ProductCategory $productCategory){
        try{
            return new ProductCategoryResource($this->productCategoryService->show($productCategory));
        }catch(Exception $e){
            return response(['status' => false, 'message' => $e->getMessage()],422);
        }
    }

     public function export(PaginateRequest $request)
{
    $categories = $this->productCategoryService->list($request);
    $export = new ProductCategoryExport($categories);

    return $export->download('ProductCategory.xlsx');
}

    public function import(Request $request)
{
    try {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:20480'
        ]);

        $import = new ProductCategoryImport();
        $import->import($request->file('file')->getRealPath());

        return response([
            'status' => true,
            'message' => 'Product categories imported successfully.'
        ]);

    } catch (Exception $e) {
        return response([
            'status' => false,
            'message' => $e->getMessage()
        ], 422);
    }
}

   public function downloadAttechment(string $fileName)
{
    try {
        $filePath = storage_path("app/attachments/{$fileName}");

        if (!file_exists($filePath)) {
            return response([
                'status' => false,
                'message' => "File not found: {$fileName}"
            ], 404);
        }

        return response()->download($filePath, $fileName, [
            'Content-Type' => mime_content_type($filePath),
        ]);

    } catch (Exception $e) {
        return response([
            'status' => false,
            'message' => $e->getMessage()
        ], 422);
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
