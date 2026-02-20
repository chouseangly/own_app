<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductCategoryResource extends JsonResource{

    public function toArray(Request $request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'parent_category' => optional($this->parent_category)->name,
            'status' => $this->status,
            'parent_id' => $this->parent_id,
            'thumb' => $this->thumb,
            'cover' => $this->cover
        ];
    }
}
