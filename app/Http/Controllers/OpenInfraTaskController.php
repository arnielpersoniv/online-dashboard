<?php

namespace App\Http\Controllers;

use App\Helpers\LogActivity;
use App\Http\Requests\OpenInfraRequest;
use App\Models\OpenInfraTask;
use App\Services\AgentDataServices;
use App\Services\OpenInfraTaskService;
use App\Traits\ResponseTraits;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OpenInfraTaskController extends Controller
{

    use ResponseTraits;

    protected $model, $service, $agentservices;

    public function __construct()
    {
        $this->model = new OpenInfraTask();
        $this->service = new OpenInfraTaskService();
        $this->agentservices = new AgentDataServices();
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
    public function store(OpenInfraRequest $request)
    {
        // Assuming 'timezone' is sent as a parameter from the client
        $timezone = $request->input('timezone');
        //return $timezone;
        // Store the timezone in the session or user preferences (optional)
        config(['app.timezone' => $timezone]);
        date_default_timezone_set($timezone);

        $result = $this->successResponse('Task Successfully Started');
        try {
            $count = 0;
            if ($request->edit_id === null) {
                $count = $this->model->where('agent_id', auth()->user()->id)->where('status', 'Pending')->count();
                if ($count == 0) {
                    $request['agent_id'] = Auth::user()->id;
                    if ($request['status'] == 'Pending') {
                        $request['time_start'] = date("Y-m-d")." ".$request->txt_timestart;
                    } else {
                        $request['time_start'] = date("Y-m-d")." ".$request->txt_timestart;
                        $request['time_end'] = date("Y-m-d")." ".$request->txt_timeend;
                    }

                    $datas = $this->model->create($request->except(['edit_id','txt_timestart','txt_timeend','timezone']));
                    $datas['timestart'] = date('H:i:s',strtotime($datas->time_start));
                    $result["data"] = $datas;
                    $result["action"] = 'store';
                } else
                    $result = $this->checkingResponse('<center>You have an ongoing Task</center>');
            } 
            else {
                $data = [
                    'lid_no'            => $request['lid_no'],
                    'category'          => $request['category'],
                    'adhoc_category'    => $request['adhoc_category'],
                    'task'              => $request['task'],
                    'adhoc_task'        => $request['adhoc_task'],
                    'status'            => $request['status'],
                    'txt_timeend'       => $request['txt_timeend'],
                ];
                $result = $this->update($request->edit_id, $data);
            }
        } catch (\Throwable $th) {
            $result = $this->errorResponse($th);
        }
        LogActivity::addToLog(Auth::user()->id, 'User Task', $request['lid_no'] . ' was released', $request['status']);

        return $this->returnResponse($result);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\OpenInfraTask  $openInfraTask
     * @return \Illuminate\Http\Response
     */
    public function show($lid_no)
    {
        $result = $this->successResponse('Task Successfully Retrieve');
        try {
            $data = $this->model->where('agent_id',auth()->user()->id)->whereRaw("status =?", 'Pending')->whereRaw("lid_no =?", $lid_no)->first();
            if ($data)
            {
                $data['timestart'] = date('H:i:s',strtotime($data->time_start));
                $result = $data;
            }
            else
                $result = 0;
        } catch (\Throwable $th) {
            $result = $this->errorResponse($th);
        }

        return $this->returnResponse($result);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\OpenInfraTask  $openInfraTask
     * @return \Illuminate\Http\Response
     */
    public function update($id, $data)
    {
        $result = ($data['status'] == 'Done') ? $this->successResponse('Task Successfully Completed') : $this->successResponse('Task Successfully Modified');
        try {
            if($data["status"] == 'Pending'){
                $datas = [
                    'lid_no'            => $data['lid_no'],
                    'category'          => $data['category'],
                    'adhoc_category'    => $data['adhoc_category'],
                    'task'              => $data['task'],
                    'adhoc_task'        => $data['adhoc_task'],
                    'status'            => $data['status'],
                ];
            }else{
                $datas = [
                    'lid_no'            => $data['lid_no'],
                    'category'          => $data['category'],
                    'adhoc_category'    => $data['adhoc_category'],
                    'task'              => $data['task'],
                    'adhoc_task'        => $data['adhoc_task'],
                    'status'            => $data['status'],
                    'time_end'          => date("Y-m-d")." ".$data['txt_timeend'],
                ];
            }
            $this->model->findOrFail($id)->update($datas);
            $result["action"] = 'update';
        } catch (\Throwable $th) {
            $result = $this->errorResponse($th);
        }
        LogActivity::addToLog(Auth::user()->id, 'User Task', $data['lid_no'] . ' was updated', $result['status']);

        return $result;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\OpenInfraTask  $openInfraTask
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

        LogActivity::addToLog(Auth::user()->id,'User Task', $data->lid_no.' was removed',$result['status']);
        return $this->returnResponse($result);
    }

    public function showRunningData(Request $request) 
    {
       $result = $this->successResponse('Data Successfully Loaded');
        try {
           $result["data"] = $this->agentservices->showAgentData($request->all());
        } catch (\Throwable $th) {
            $result = $this->errorResponse($th);
        }

        return $this->returnResponse($result);
    }
}
