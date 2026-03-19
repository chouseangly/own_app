<?php

namespace App\Models;

use App\Models\ProductAttribute;
use App\Models\ProductAttributeOption;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Staudenmeir\LaravelAdjacencyList\Eloquent\HasRecursiveRelationships;

class ProductVariation extends Model implements HasMedia
{
    use HasRecursiveRelationships, InteractsWithMedia;

    protected $table = "product_variations";

    protected $fillable = [
        'product_id',
        'product_attribute_id',
        'product_attribute_option_id',
        'price',
        'sku',
        'parent_id',
        'order',
    ];

    protected $casts = [
        'id' => 'integer',
        'product_id' => 'integer',
        'product_attribute_id' => 'integer',
        'product_attribute_option_id' => 'integer',
        'price' => 'decimal:6',
        'sku' => 'string',
        'parent_id' => 'integer',
        'order' => 'integer',
    ];


    public function getMediaUrlAttribute(){
        if(!empty($this->getFirstMediaUrl('product-variation-barcode'))){
            return $this->getFirstMediaUrl('product-variation-barcode');
        }
        return '';
    }

    public function product(){
        return $this->belongsTo(Product::class,'product_id','id');
    }

    public function productAttribute(){
        return $this->belongsTo(ProductAttribute::class,'product_attribute_id','id');
    }

    public function productAttributeOption(){
        return $this->belongsTo(ProductAttributeOption::class,'product_attribute_option_id','id');
    }



}
