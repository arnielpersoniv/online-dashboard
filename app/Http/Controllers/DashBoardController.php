<?php

namespace App\Http\Controllers;

use App\Services\DashBoardServices;
use App\Traits\ResponseTraits;
use Illuminate\Http\Request;

class DashBoardController extends Controller
{
    use ResponseTraits;

    protected $service;

    public function __construct()
    { 
        $this->service = new DashBoardServices;
    }

    public function loadDaily(Request $request) 
    {
       $result = $this->successResponse('Data Successfully Loaded');
        try {
           $result["data"] = $this->service->getDaily($request->all());
        } catch (\Throwable $th) {
            $result = $this->errorResponse($th);
        }

        return $this->returnResponse($result);
    }

    public function loadWeekly(Request $request) {

        $result = $this->successResponse('Data Successfully Loaded');
         try {
            $result["data"] = $this->service->getWeekly($request->all());
         } catch (\Throwable $th) {
             $result = $this->errorResponse($th);
         }
 
         return $this->returnResponse($result);
    }

    public function loadMonthly(Request $request) {

        $result = $this->successResponse('Data Successfully Loaded');
         try {
            $result["data"] = $this->service->getMonthly($request->all());
         } catch (\Throwable $th) {
             $result = $this->errorResponse($th);
         }
 
         return $this->returnResponse($result);
    }

    public function loadYearly(Request $request) {

        $result = $this->successResponse('Data Successfully Loaded');
         try {
            $result["data"] = $this->service->getYearly($request->all());
         } catch (\Throwable $th) {
             $result = $this->errorResponse($th);
         }
 
         return $this->returnResponse($result);
    }
}
