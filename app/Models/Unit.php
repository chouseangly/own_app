<?php

namespace App\Models;

use App\Enums\Status;
use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    protected $table = 'units';

    protected $fillable = ['name', 'code', 'status'];

    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
        'code' => 'string',
        'status' => 'integer'
    ];

    public function products()
    {
        return $this->hasMany(Product::class)->where(['status' => Status::ACTIVE]);
    }
}
