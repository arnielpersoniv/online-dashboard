<?php

namespace App\Http\Controllers;

use App\Helpers\LogActivity;
use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use App\Traits\ResponseTraits;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    
    use ResponseTraits;

    protected $model;

    public function __construct()
    {
        $this->model = new Category();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $result = $this->successResponse('Category Successfully Loaded');
        try {
            $result["data"] = $this->model->with('tasks','createdby','updatedby')->get();
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
    public function store(CategoryRequest $request)
    {
        $result = $this->successResponse('Category Successfully Added');
        try {
            if($request->category_id === null) {
                $request['created_by'] = Auth::user()->id;
                $this->model->create($request->except(['category_id']));
            }
            else {
                $result = $this->update($request->category_id, $request->all());
            }
        } catch (\Throwable $th) {
            $result = $this->errorResponse($th);
        }
        LogActivity::addToLog(Auth::user()->id,'New Category', $request->name.' was added' ,$result['status']);
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
        $result = $this->successResponse('Category Successfully Retrieve');
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
        $result = $this->successResponse('Category Successfully Update');
        try {
            $datas = [
                'name' => $data['name'],
                'updated_by' => Auth::user()->id,
            ];
            $data = $this->model->findOrFail($id);
            $this->model->findOrFail($id)->update($datas);
        } catch (\Throwable $th) {
            $result = $this->errorResponse($th);
        }

        LogActivity::addToLog(Auth::user()->id,'Update Category', $data['name'].' was updated' ,$result['status']);
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
        $result = $this->successResponse('Category Successfully Deleted');
        try {
            $data = $this->model->findOrFail($id);
            $this->model->findOrFail($id)->delete();
        } catch (\Throwable $th) {
            $result = $this->errorResponse($th);
        }

        LogActivity::addToLog(Auth::user()->id,'Delete Category', $data['name'].' was removed' ,$result['status']);
        return $this->returnResponse($result);
    }
}
