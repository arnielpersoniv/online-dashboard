<?php

namespace App\Http\Controllers;

use App\Services\DashBoardWipServices;
use App\Traits\ResponseTraits;
use Illuminate\Http\Request;

class DashBoardWipController extends Controller
{
    use ResponseTraits;

    protected $service;

    public function __construct()
    { 
        $this->service = new DashBoardWipServices;
    }

    public function loadAll(Request $request) 
    {
       $result = $this->successResponse('Data Successfully Loaded');
        try {
           $result["data"] = $this->service->loadAll($request->all());
        } catch (\Throwable $th) {
            $result = $this->errorResponse($th);
        }

        return $this->returnResponse($result);
    }
}
