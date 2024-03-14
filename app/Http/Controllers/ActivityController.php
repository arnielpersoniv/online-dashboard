<?php

namespace App\Http\Controllers;

use App\Helpers\LogActivity;
use App\Http\Requests\ActivityRequest;
use App\Models\Activity;
use App\Models\User;
use App\Services\TaskService;
use App\Traits\ResponseTraits;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ActivityController extends Controller
{
    use ResponseTraits;

    protected $model;
    protected $service;

    public function __construct()
    {
        $this->model = new Activity();
        $this->service = new TaskService();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $result = $this->successResponse('Task Successfully Loaded');
        try {
            // $tempdata = array_map('trim', explode('-', $request->date));
            // $data = [
            //     'start' => date("Y-m-d",strtotime($tempdata[0])),
            //     'end'   => date("Y-m-d",strtotime($tempdata[1])),
            //     'status'=> $request->params
            // ];
           $result["data"] = $this->service->loadAll($request->all());
        } catch (\Throwable $th) {
            $result = $this->errorResponse($th);
        }

        return $this->returnResponse($result);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ActivityRequest $request)
    {
        $result = $this->successResponse('Task Successfully Started');
        try {
            if($request->activity_id === null) {
                $count = $this->model->where('released_by',auth()->user()->id)->where('status','released')->orWhere('status','hold')->count();
                if($count == 0){
                    $request['released_by'] = Auth::user()->id;
                    if($request['status'] == 'released'){
                        $request['time_start'] = now();
                    }elseif ($request['status'] == 'completed'){
                        $request['time_start'] = now();
                        $request['time_end'] = now();
                    }else{
                        $request['time_start'] = now();
                        $request['time_end'] = now();
                        $request['time_resume'] = now();
                    }
                    
                    $this->model->create($request->except(['activity_id']));
                }
                else
                    $result = $this->checkingResponse('<center>You have an ongoing Task</center>');
            }
            else {
                $data = [
                    'order_no'      => $request['order_no'],
                    'account_no'    => $request['account_no'],
                    'status_id'     => $request['status_id'],
                    'category_id'   => $request['category_id'],
                    'task_id'       => $request['task_id'],
                ];
                $result = $this->update($request->activity_id, $data);
            }
           
        } catch (\Throwable $th) {
            $result = $this->errorResponse($th);
        }
        LogActivity::addToLog(Auth::user()->id,'User Task', $request['account_no'].' was released',$result['status']);

        return $this->returnResponse($result);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $result = $this->successResponse('Task Successfully Retrieve');
        try {
            $result = $this->model->findOrFail($id);
        } catch (\Throwable $th) {
            $result = $this->errorResponse($th);
        }

        return $this->returnResponse($result);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update($id, $data)
    {
        $result = $this->successResponse('Task Successfully Update');
        try {
            $this->model->findOrFail($id)->update($data);
        } catch (\Throwable $th) {
            $result = $this->errorResponse($th);
        }
        LogActivity::addToLog(Auth::user()->id,'User Task', $data['account_no'].' was updated',$result['status']);
        
        return $result;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $result = $this->successResponse('Task Successfully Deleted');
        try {
            $data = $this->model->findOrFail($id);
            $this->model->findOrFail($id)->delete();
        } catch (\Throwable $th) {
            $result = $this->errorResponse($th);
        }

        LogActivity::addToLog(Auth::user()->id,'User Task', $data->account_no.' was removed',$result['status']);
        return $this->returnResponse($result);
    }

    public function action(Request $request)
    {
        if($request->action == 'hold'){
            $status = 'Hold';
            $data = [
                'status'      => 'hold',
                'hold_reason' => $request->reason,
                'time_hold'   => now(),
                'time_resume' => null
            ];
        }else if($request->action == 'resume'){
            $status = 'Resume';
            $data = [
                'status'      => 'released',
                'time_resume' => now()
            ];
        }
        else{
            $status = 'Completed';
            $data = [
                'status'     => 'completed',
                'time_end'   => now()
            ];
        }
        
        $result = $this->successResponse('Task Successfully '.$status);

        try {
            $data = $this->model->findOrFail($request->id);
            $this->model->findOrFail($request->id)->update($data);
        } catch (\Throwable $th) {
            $result = $this->errorResponse($th);
        }

        LogActivity::addToLog(Auth::user()->id,'User Task', $data->account_no.' was '.$status ,$result['status']);
        return $this->returnResponse($result);
    }

}
