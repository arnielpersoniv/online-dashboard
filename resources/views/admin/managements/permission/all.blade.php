@extends('layouts.layout')
@section('content')
<div class="container-fluid">
    <div class="row-fluid">
        <div class="widget-box">
            <div class="widget-title"><span class="icon"><i class="icon-tasks"></i></span>
                <h5>Permission</h5>
            </div>
            <div class="widget-content">
                <div class="row-fluid">
                    <div class="span4">
                        <div class="widget-box">
                            <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
                                <h5>New Entry</h5>
                            </div>
                            <div class="widget-content nopadding">
                                <form class="form-horizontal" method="post" id="form_permission" novalidate="novalidate">
                                    @csrf <div class="control-group">
                                        <label class="control-label required">Name of Permission</label>
                                        <div class="controls">
                                            <select class="span11" name="name" id="permission_name">
                                                <option value="" selected>Select Here</option>
                                                <option value="superadmin">Super Admin</option>
                                                <option value="admin">Admin</option>
                                                <option value="agent">Agent</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-actions">
                                        <button type="submit" id="btn_save" class="btn btn-success">Save</button>
                                        <button type="reset" class="btn btn-danger">Cancel</button>
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
                                <table class="table table-bordered data-table" id="tbl_permission">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
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
<script src="{{asset('scripts/admin/permission.js')}}"></script>
@endsection