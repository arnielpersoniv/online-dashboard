<?php

namespace App\Http\Controllers;

use App\Http\Requests\StatusRequest;
use App\Models\Status;
use App\Traits\ResponseTraits;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StatusController extends Controller
{
    use ResponseTraits;

    protected $model;

    public function __construct()
    {
        $this->model = new Status();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $result = $this->successResponse('Status Successfully Loaded');
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
    public function store(StatusRequest $request)
    {
        $result = $this->successResponse('Status Successfully Added');
        try {
            if($request->status_id === null) {
                $request['created_by'] = Auth::user()->id;
                $this->model->create($request->except(['status_id']));
            }
            else {
                $result = $this->update($request->status_id, $request->all());
            }
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
    public function show($id)
    {
        $result = $this->successResponse('Status Successfully Retrieve');
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
        $result = $this->successResponse('Status Successfully Update');
        try {
            $datas = [
                'name' => $data['name'],
                'updated_by' => Auth::user()->id,
            ];
            $this->model->findOrFail($id)->update($datas);
        } catch (\Throwable $th) {
            $result = $this->errorResponse($th);
        }

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
        $result = $this->successResponse('Status Successfully Deleted');
        try {
            $this->model->findOrFail($id)->delete();
        } catch (\Throwable $th) {
            $result = $this->errorResponse($th);
        }

        return $this->returnResponse($result);
    }
}
