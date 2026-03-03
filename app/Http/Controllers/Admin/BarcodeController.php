<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\PaginateRequest;
use App\Http\Resources\BarcodeResource;
use App\Services\BarcodeService;
use Exception;
use Illuminate\Http\Request;

class BarcodeController extends AdminController
{
    private BarcodeService $barcodeService;

    public function __construct(BarcodeService $barcodeService)
    {
         parent::__construct();
         $this->barcodeService = $barcodeService;
    }

    public function index(PaginateRequest $request){
        try{
            return BarcodeResource::collection($this->barcodeService->list($request));
        }catch(Exception $e){
            return response(['status' => false , 'message' => $e->getMessage()],422);
        }
    }
}
