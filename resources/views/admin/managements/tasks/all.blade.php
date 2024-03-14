@extends('layouts.layout')
@section('css')
<link rel="stylesheet" href="{{asset('themes/css/select2.min.css')}}" />
<link rel="stylesheet" href="{{asset('css/admin/dashboard.css')}}" />
@endsection
@section('content')
<div class="container-fluid">
    <div class="row-fluid">
        <div class="widget-box">
            <div class="widget-title"><span class="icon"><i class="icon-tasks"></i></span>
                <h5>Tasks</h5>
            </div>
            <div class="widget-content">
                <div class="row-fluid">
                    <div class="span4">
                        <div class="widget-box">
                            <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
                                <h5>New Task</h5>
                            </div>
                            <div class="widget-content nopadding">
                                <form class="form-horizontal" method="post" id="form_task" novalidate="novalidate">
                                    @csrf
                                    <input type="hidden" id="edit_id" name="task_id">
                                    <div class="control-group">
                                        <label class="control-label required">Category :</label>
                                        <div class="controls">
                                            <select class="span11 select2" id="category_id" name="category_id">
                                            </select>
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label required">Task Name</label>
                                        <div class="controls">
                                            <input type="text" class="span11" name="name" id="task_name" placeholder="Task Name" />
                                        </div>
                                    </div>
                                    <div class="form-actions">
                                        <button type="submit" id="btn_save" class="btn btn-success">Save</button>
                                        <button type="reset" id="btn_cancel" class="btn btn-danger">Cancel</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="span8">
                        <div class="widget-box">
                            <div class="widget-title">
                                <span class="icon"><i class="icon-th"></i></span>
                                <h5>Lists of Categories</h5>
                            </div>
                            <div class="widget-content nopadding">
                                <table class="table table-bordered" id="tbl_task">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Category</th>
                                            <th>Name</th>
                                            <th>Created By</th>
                                            <th>Date Created</th>
                                            <th>Updated By</th>
                                            <th>Date Updated</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                                <div class="overlay div-spinner" id="loading"><i class="fa fa-spinner fa-pulse i-spinner"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <hr>
</div>
@endsection
@section('js')
<script src="{{asset('themes/js/select2.min.js')}}"></script>
<script src="{{asset('scripts/admin/task.js')}}"></script>
@endsection