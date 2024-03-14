<?php

namespace App\Http\Controllers;

use App\Helpers\LogActivity;
use App\Http\Requests\UserRequest;
use App\Models\User;
use App\Traits\ResponseTraits;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    use ResponseTraits;

    protected $model;

    public function __construct()
    {
        $this->model = new User();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $result = $this->successResponse('User Successfully Loaded');
        try {
            $result["data"] = $this->model->with('createdby','updatedby')->get();
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
    public function store(UserRequest $request)
    {
        $result = $this->successResponse('User Successfully Added');
        try {
            if($request->user_id === null) {
                $request['created_by'] = Auth::user()->id;
                $this->model->create($request->except(['user_id']));
            }
            else {
                $result = $this->update($request->user_id, $request->all());
            }
        } catch (\Throwable $th) {
            $result = $this->errorResponse($th);
        }
        LogActivity::addToLog(Auth::user()->id,'New User', $request->name.' was added' ,$result['status']);
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
        $result = $this->successResponse('User Successfully Retrieve');
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
        $result = $this->successResponse('User Successfully Update');
        try {
            $datas = [
                'emp_id'        => $data['emp_id'],
                'name'          => $data['name'],
                'email'         => $data['email'],
                'role'          => $data['role'],
                'updated_by'    => Auth::user()->id,
            ];
            $this->model->findOrFail($id)->update($datas);
        } catch (\Throwable $th) {
            $result = $this->errorResponse($th);
        }

        LogActivity::addToLog(Auth::user()->id,'Update User', $data['name'].' was updated' ,$result['status']);
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
        $result = $this->successResponse('User Successfully Deleted');
        try {
            $data = $this->model->findOrFail($id);
            $this->model->findOrFail($id)->delete();
        } catch (\Throwable $th) {
            $result = $this->errorResponse($th);
        }
        LogActivity::addToLog(Auth::user()->id,'Remove User', $data['name'].' was removed' ,$result['status']);
        return $this->returnResponse($result);
    }
}
