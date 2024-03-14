<?php

namespace App\Http\Controllers;

use App\Services\AttendanceServices;
use App\Traits\ResponseTraits;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    use ResponseTraits;

    protected $service;

    public function __construct()
    { 
        $this->service = new AttendanceServices;
    }

    public function loadAttendance($where) 
    {
       $result = $this->successResponse('Attendance Successfully Loaded');
        try {
           $result["data"] = $this->service->getAttendance($where);
        } catch (\Throwable $th) {
            $result = $this->errorResponse($th);
        }

        return $this->returnResponse($result);
    }

}
