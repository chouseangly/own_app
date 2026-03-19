<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\PaginateRequest;
use App\Http\Resources\SimpleProductBrandResource;
use App\Http\Resources\SimpleProductDetailResource;
use App\Http\Resources\SimpleProductResource;
use App\Models\Product;
use App\Services\ProductService;
use Exception;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    private ProductService $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function index(PaginateRequest $request){
        try{
            return SimpleProductResource::collection($this->productService->list($request));
        }catch (Exception $e){
            return response()->json([
                'message' => $e->getMessage()
            ], 422);
        }
    }

    public function show (Product $product , Request $request){
        try{

            return new  SimpleProductDetailResource($this->productService->showWithRelation($product,$request));

        }catch (Exception $e){
            return response()->json([
                'message' => $e->getMessage()
            ], 422);
        }
    }

    public function showWithTrashed(Product $product , Request $request){
        try{
            return new SimpleProductDetailResource($this->productService->showWithTrashed($product,$request));
        }catch(Exception $e){
            return response()->json(['status' => false , 'message' => $e->getMessage()],422);
        }
    }

    public function mostPopularProducts(PaginateRequest $request){
        try{
            return SimpleProductResource::collection($this->productService->mostPopularProducts($request));

        }catch(Exception $e){
            return response()->json(['status' => false , 'message' => $e->getMessage()],422);
        }
    }

    public function categoryWiseProducts(Request $request){
        try{

            return new SimpleProductBrandResource($this->productService->categoryWiseProducts($request));
        }catch(Exception $e){
            return response()->json(['status' => false , 'message' => $e->getMessage()],422);
        }
    }
}
