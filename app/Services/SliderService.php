<?php

namespace App\Services;

use App\Http\Requests\PaginateRequest;
use App\Http\Requests\SliderRequest;
use App\Libraries\QueryExceptionLibrary;
use App\Models\Slider;
use Exception;
use Illuminate\Support\Facades\Log;

class SliderService
{

    protected $sliderFilter = [
        'title',
        'description',
        'status'
    ];

    protected $exceptFilter = [
        'excepts'
    ];

    public function list(PaginateRequest $request)
    {
        try {
            $requests = $request->all();
            $method = $request->get('paginate', 0) == 1 ? 'paginate' : 'get';
            $methodValue = $request->get('paginate', 0) == 1 ? $request->get('per_page', 10) : '*';
            $orderColumn = $request->get('order_column') ?? 'id';
            $orderType = $request->get('order_type') ?? 'desc';

            return Slider::with('media')->where(function ($query) use ($request, $requests) {
                foreach ($requests as $key => $request) {
                    if (in_array($key, $this->sliderFilter)) {
                        $query->where($key, 'like', '%' . $request . '%');
                    }

                    if (in_array($key, $this->exceptFilter)) {
                        $explodes = explode('|', $request);
                        if (is_array($explodes)) {
                            foreach ($explodes as $explode) {
                                $query->where('id', '!=', $explode);
                            }
                        }
                    }
                }
            })->orderBy($orderColumn, $orderType)->$method(
                $methodValue
            );
        } catch (Exception $e) {
            Log::info($e->getMessage());
            return new Exception(QueryExceptionLibrary::message($e), 422);
        }
    }

    public function store(SliderRequest $request)
    {
        try {

            $slider = Slider::create($request->validated() + ['link' => $request->link]);
            if ($request->image) {
                $slider->addMediaFromRequest('image')->toMediaCollection('slider');
            }
            return $slider;
        } catch (Exception $e) {
            Log::info($e->getMessage());
            return new Exception(QueryExceptionLibrary::message($e), 422);
        }
    }

    public function show(Slider $slider)
    {
        try {

            return $slider;
        } catch (Exception $e) {
            Log::info($e->getMessage());
            return new Exception(QueryExceptionLibrary::message($e), 422);
        }
    }

    public function update(SliderRequest $request, Slider $slider)
    {
        try {

            $slider->update($request->validated() + ['link' => $request->link]);
            if ($request->image) {
                $slider->clearMediaCollection('slider');
                $slider->addMediaFromRequest('image')->toMediaCollection('slider');
            }
            return $slider;
        } catch (Exception $e) {
            Log::info($e->getMessage());
            return new Exception(QueryExceptionLibrary::message($e), 422);
        }
    }

    public function destroy(Slider $slider)
    {
        try {

            $slider->delete();
        } catch (Exception $e) {
            Log::info($e->getMessage());
            return new Exception(QueryExceptionLibrary::message($e), 422);
        }
    }
}
