<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductAttribute extends Model
{
    use HasFactory;

    protected $table = "product_attributes";
    protected $fillable = [
        'id',
        'name',
    ];

    public function productAttributeOptions(){
        return $this->hasMany(ProductAttributeOption::class,'product_attribute_id','id');
    }
}
