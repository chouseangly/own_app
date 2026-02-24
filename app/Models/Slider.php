<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Slider extends Model implements HasMedia{

    use InteractsWithMedia;
    protected $table = 'sliders';

    protected $fillable = [
        'title',
        'link',
        'description',
        'status'
    ];

    protected $casts = [
        'id' => 'integer',
        'title' => 'string',
        'link' => 'string',
        'description' => 'string',
        'status' => 'string'
    ];

    public function getImageAttribute() {
        if(!empty($this->getFirstMedia('slider'))){
            $slider = $this->getMedia('slider')->last();
            return $slider->getUrl('cover');
        }
        return asset('images/required/profile.png');
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('cover')->fit(Fit::Fill,1689,600)->keepOriginalImageFormat()->sharpen(10);
    }
}
