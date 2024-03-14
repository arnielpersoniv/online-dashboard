<?php

namespace App\Http\Controllers;

use App\Helpers\LogActivity;
use App\Http\Requests\TaskRequest;
use App\Models\Task;
use App\Traits\ResponseTraits;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    use ResponseTraits;

    protected $model;

    public function __construct()
    {
        $this->model = new Task();
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
            $result["data"] = $this->model->with('category','createdby','updatedby')->get();
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
    public function store(TaskRequest $request)
    {
        $result = $this->successResponse('Task Successfully Added');
        try {
            if($request->task_id === null) {
                $request['created_by'] = Auth::user()->id;
                $this->model->create($request->except(['task_id']));
            }
            else {
                $result = $this->update($request->task_id, $request->all());
            }
        } catch (\Throwable $th) {
            $result = $this->errorResponse($th);
        }
        LogActivity::addToLog(Auth::user()->id,'New Entry', $request->name.' was added' ,$result['status']);
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
     * Display the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit($categoryId)
    {
        $result = $this->successResponse('Task Successfully Retrieve');
        try {
            $result = $this->model->where('category_id',$categoryId)->get();
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
            $datas = [
                'category_id' => $data['category_id'],
                'name'        => $data['name'],
                'updated_by'  => Auth::user()->id,
            ];
            $this->model->findOrFail($id)->update($datas);
        } catch (\Throwable $th) {
            $result = $this->errorResponse($th);
        }
        LogActivity::addToLog(Auth::user()->id,'Modify', $data['name'].' was updated' ,$result['status']);
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

        LogActivity::addToLog(Auth::user()->id,'Modify', $data['name'].' was updated' ,$result['status']);
        return $this->returnResponse($result);
    }
}
