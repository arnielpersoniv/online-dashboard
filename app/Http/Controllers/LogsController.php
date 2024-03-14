<?php

namespace App\Http\Controllers;

use App\Models\Logs;
use App\Traits\ResponseTraits;
use Illuminate\Http\Request;

class LogsController extends Controller
{
    use ResponseTraits;

    protected $model;

    public function __construct()
    {
        $this->model = new Logs();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $result = $this->successResponse('Task Successfully Loaded');
        try {
            $result["data"] = $this->model->with('users')->orderByDesc('id')->get();
        } catch (\Throwable $th) {
            $result = $this->errorResponse($th);
        }

        return $this->returnResponse($result);
    }
}
