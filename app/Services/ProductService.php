<?php

namespace App\Services;

use App\Enums\Status;
use App\Http\Requests\PaginateRequest;
use App\Libraries\QueryExceptionLibrary;
use App\Models\Product;
use App\Models\ProductAttributeOption;
use App\Models\ProductCategory;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx\Rels;
use Symfony\Component\HttpFoundation\Request;

class ProductService
{

    public object $product;
    protected array $productFilter = [
        'name',
        'sku',
        'slug',
        'buying_price',
        'selling_price',
        'product_category_id',
        'product_brand_id',
        'barcode_id',
        'tax_id',
        'unit_id',
        'show_stock_out',
        'status',
        'can_purchasable',
        'refundable',
        'weight',
        'order',
        'except'
    ];

    public function list(PaginateRequest $request){
        try{
            $requests = $request->all();
            $method = $request->get('paginate',0)  == 1 ? 'paginate' : 'get';
            $methodValue = $request->get('paginate',0) == 1 ? $request->get('per_page',10) : '*';
            $orderColumn = $request->get('order_column') ?? 'id';
            $orderType = $request->get('order_type') ?? 'desc';
            return Product::with('media','category','brand','taxes','tags','reviews')
            ->with(['wishlist' => fn($query) => $query->where('user_id', Auth::check() ? Auth::user()->id : 0)])
            ->withReviewRating()->where(function ($query) use ($request, $requests){
                foreach($requests as $key => $request){
                    if(in_array($key,$this->productFilter)){
                        if($key == 'except'){
                            $explodes = explode('|', $request);
                            if (count($explodes)) {
                                foreach ($explodes as $explode) {
                                    $query->where('id', '!=', $explode);
                                }
                            }
                        }
                        else{
                             if ($key == "product_category_id") {
                                $query->where($key, $request);
                            } elseif ($key == "tax_id") {
                                $query->whereHas('taxes', function ($q) use ($key, $request) {
                                    $q->where($key, $request);
                                });
                            } else {
                                $query->where($key, 'like', '%' . $request . '%');
                            }
                        }
                    }
                }
            })->orderBy($orderColumn, $orderType)->$method(
                $methodValue);

        }catch(Exception $e){
            Log::info($e->getMessage());
            throw new Exception(QueryExceptionLibrary::message($e),422);

        }
    }

    public function showWithRelation(Product $product , Request $request){
        try{

            return Product::with('media','videos','category','unit','taxes')
                ->with(['seo' => fn($query) => $query->with('media')])
                ->withSum('stockItems','quantity')
                ->with(['wishlist' => fn($query) => $query->where('user_id', Auth::check() ? Auth::user()->id : 0)])
                ->with(['reviews' => fn($query) => $query->with('user','media')->take($request->get('review_limit',3))])
                ->withReviewRating()
                ->where(['id' => $product->id, 'status' => Status::ACTIVE])
                ->first();

        }catch(Exception $e){
            Log::info($e->getMessage());
            throw new Exception(QueryExceptionLibrary::message($e),422);

        }
    }

    public function showWithTrashed(Product $product , Request $request){
        try{

            return Product::with('media','videos','category','unit','taxes')
            ->with(['seo' => fn($query) => $query->with('media')])
            ->withSum('stockItems','quantity')
            ->with(['wishlist' => fn($query) => $query->where('user_id',Auth::check() ? Auth::user()->id : 0)])
            ->with(['reviews' => fn($query) => $query->with('user','media')->take($request->get('review_limit',3))])
            ->withReviewRating()
            ->where(['id' => $product->id , 'status' => Status::ACTIVE])
            ->withTrashed()
            ->first();

        }catch(Exception $e){
            Log::info($e->getMessage());
            throw new Exception(QueryExceptionLibrary::message($e),422);
        }
    }

    public function mostPopularProducts(PaginateRequest $request){
        try{

            $method = $request->get('paginate',0) == 1 ? 'paginate' : 'get';
            $methodValue = $request->get('paginate',0) == 1 ? $request->get('per_page',32) : '*';
            $orderColumn = $request->get('order_column') ?? 'id';
            $orderType = $request->get('order_type') ?? 'desc';
            $rand = $request->get('rand',0) > 0 ? $request->get('rand') : 0 ;

            return Product::select('products.id','products.name','products.sku','products.slug','products.selling_price','products.variantion_price',
                'products.add_to_flash_sell' , 'products.offer_start_date', 'products.offer_end_date','products.discount','products.status'
            )
            ->with(['wishlist' => fn($query) => $query->where('user_id',Auth::check() ? Auth::user()->id : 0)])
            ->withReviewRating()
            ->withCount('orderCountable')
            ->where(['status' => Status::ACTIVE])
            ->orderBy('order_countable_count')
            ->randAndLimitOrOrderBy($rand,$orderColumn,$orderType)
            ->$method($methodValue);

        }catch(Exception $e){
            Log::info($e->getMessage());
            throw new Exception(QueryExceptionLibrary::message($e),422);
        }
    }


    public function categoryWiseProducts (Request $request){
        try{
            $customProductFilter = [
                'name',
                'sku',
                'slug',
                'status'
            ];

            $customProductFilterMask = [
                'name' => 'products.name',
                'sku' => 'products.sku',
                'slug' => 'products.slug',
                'status' => 'products.status'
            ];

             $categories = [];

            if ($request->has('category')) {
                
                if (!blank($request->category)) {
                    $categories = ProductCategory::where(['slug' => $request->category])->first();
                    if ($categories) {
                        $categories = $categories->descendantsAndSelf->toArray();
                    } else {
                        $categories = [];
                    }
                }
            }


            $productCategory = Product::select('products.id', 'products.name', 'products.sku', 'products.slug', 'products.status', 'products.product_category_id', 'products.product_brand_id', 'products.variation_price')->with('brand', 'variations')->where(function ($query) use ($request, $categories) {
                if (count($categories)) {
                    $i = 0;
                    foreach ($categories as $category) {
                        if ($i === 0) {
                            $query->where('product_category_id', $category['id']);
                        } else {
                            $query->orWhere('product_category_id', $category['id']);
                        }
                        $i++;
                    }
                }
            })->where(function ($query) use ($request, $customProductFilter, $customProductFilterMask) {
                foreach ($request->all() as $key => $req) {
                    if (in_array($key, $customProductFilter)) {
                        $query->where($customProductFilterMask[$key], 'like', '%' . $req . '%');
                    }
                }
            })->get();

            $perPage     = $request->post('per_page', 30);
            $orderColumn = 'products.name';
            $orderType   = 'asc';
            if ($request->post('sort_by') == 'newest') {
                $orderColumn = 'id';
                $orderType   = 'desc';
            } else if ($request->post('sort_by') == 'price_low_to_high') {
                $orderColumn = 'products.variation_price';
            } else if ($request->post('sort_by') == 'price_high_to_low') {
                $orderColumn = 'products.variation_price';
                $orderType   = 'desc';
            } else if ($request->post('sort_by') == 'top_rated') {
                $orderColumn = 'rating_star';
                $orderType   = 'desc';
            }

            $products = Product::select('products.id', 'products.name', 'products.sku', 'products.slug', 'products.product_category_id', 'products.product_brand_id', 'products.selling_price', 'products.variation_price', 'products.add_to_flash_sale', 'products.offer_start_date', 'products.offer_end_date', 'products.discount', 'products.status')
                ->withReviewRating()
                ->with(['wishlist' => fn($query) => $query->where('user_id', Auth::check() ? Auth::user()->id : 0)])
                ->with('media', 'brand', 'variations', 'reviews')
                ->where(function ($query) use ($request, $categories) {
                    if (count($categories)) {
                        $i = 0;
                        foreach ($categories as $category) {
                            if ($i === 0) {
                                $query->where('product_category_id', $category['id']);
                            } else {
                                $query->orWhere('product_category_id', $category['id']);
                            }
                            $i++;
                        }
                    }
                })->where(function ($query) use ($request) {
                    if (!blank($request->brand)) {
                        $brands = json_decode($request->brand);
                        if (count($brands)) {
                            $i = 0;
                            foreach ($brands as $brand) {
                                if ($i === 0) {
                                    $query->where('product_brand_id', $brand);
                                } else {
                                    $query->orWhere('product_brand_id', $brand);
                                }
                                $i++;
                            }
                        }
                    }
                })->where(function ($query) use ($request, $customProductFilter, $customProductFilterMask) {
                    foreach ($request->all() as $key => $req) {
                        if (in_array($key, $customProductFilter)) {
                            $query->where($customProductFilterMask[$key], 'like', '%' . $req . '%');
                        }
                    }
                })->where(function ($query) use ($request) {
                    if (!blank($request->variation)) {
                        $variations = json_decode($request->variation);
                        if (count($variations)) {
                            $arrays = [];
                            foreach ($variations as $variation) {
                                $arrays[$variation->attribute][] = [
                                    'option' => $variation->option
                                ];
                            }

                            foreach ($arrays as $key => $array) {
                                $query->whereHas('variations', function ($q) use ($key, $array) {
                                    $i = 0;
                                    foreach ($array as $a) {
                                        if ($i === 0) {
                                            $q->where('product_attribute_id', $key)->where('product_attribute_option_id', $a['option']);
                                        } else {
                                            $q->orWhere('product_attribute_id', $key)->where('product_attribute_option_id', $a['option']);
                                        }
                                        $i++;
                                    }
                                });
                            }
                        }
                    }
                })->orderBy($orderColumn, $orderType)->where(function ($query) use ($request) {
                    if ($request->min_price >= 0 && $request->max_price > 0) {
                        $query->whereBetween('variation_price', [$request->min_price, $request->max_price]);
                    }
                })->paginate($perPage);

            $variations = $productCategory->map(function ($query) {
                return $query->variations;
            });

            $variationArray         = [];
            $productAttributeOption = ProductAttributeOption::get()->pluck('name', 'id')->toArray();
            if ($variations) {
                foreach ($variations->toArray() as $variation) {
                    if (count($variation)) {
                        foreach ($variation as $v) {
                            if (isset($variationArray[Str::slug($v['product_attribute']['name'], '_')])) {
                                $status = true;
                                foreach ($variationArray[Str::slug($v['product_attribute']['name'], '_')] as $va) {
                                    if ($v['product_attribute_option_id'] == $va['product_attribute_option_id']) {
                                        $status = false;
                                    }
                                }
                                if ($status) {
                                    $variationArray[Str::slug($v['product_attribute']['name'], '_')][] = [
                                        'attribute_name'              => $v['product_attribute']['name'],
                                        'attribute_option_name'       => isset($productAttributeOption[$v['product_attribute_option_id']]) ? $productAttributeOption[$v['product_attribute_option_id']] : '',
                                        'product_attribute_id'        => (int) $v['product_attribute_id'],
                                        "product_attribute_option_id" => (int) $v['product_attribute_option_id'],
                                    ];
                                }
                            } else {
                                $variationArray[Str::slug($v['product_attribute']['name'], '_')][] = [
                                    'attribute_name'              => $v['product_attribute']['name'],
                                    'attribute_option_name'       => isset($productAttributeOption[$v['product_attribute_option_id']]) ? $productAttributeOption[$v['product_attribute_option_id']] : '',
                                    'product_attribute_id'        => (int) $v['product_attribute_id'],
                                    "product_attribute_option_id" => (int) $v['product_attribute_option_id']
                                ];
                            }
                        }
                    }
                }
            }

            return collect([
                'products'   => $products,
                'brands'     => $productCategory->map(function ($query) {
                    return $query->brand;
                })->whereNotNull('id')->unique('id')->values()->all(),
                'variations' => $variationArray,
                'max_price'  => ceil($productCategory->max('variation_price') + 50),
            ]);

        }catch(Exception $e){
            Log::info($e->getMessage());
            throw new Exception(QueryExceptionLibrary::message($e),422);
        }
    }
}
