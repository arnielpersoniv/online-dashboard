@extends('layouts.layout')
@section('css')
<link rel="stylesheet" href="{{asset('css/admin/dashboard.css')}}" />
@endsection
@section('content')
<div class="container-fluid">
    <div class="row-fluid">
        <div class="widget-box">
            <div class="widget-title"><span class="icon"><i class="icon-tasks"></i></span>
                <h5>Users</h5>
            </div>
            <div class="widget-content">
                <div class="row-fluid">
                    <div class="span4">
                        <div class="widget-box">
                            <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
                                <h5>Entry</h5>
                            </div>
                            <div class="widget-content nopadding">
                                <form class="form-horizontal" id="form_user">
                                    <input type="hidden" id="edit_id" name="user_id">
                                    <div class="control-group">
                                        <label class="control-label required">ID No. :</label>
                                        <div class="controls">
                                            <input type="text" class="span11" name="emp_id" id="emp_id" placeholder="Employee No." />
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label required">Formal Name</label>
                                        <div class="controls">
                                            <input type="text" class="span11" name="name" id="fullname" placeholder="First Name and Last Name" />
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label required">Email Address</label>
                                        <div class="controls">
                                            <input type="email" class="span11" name="email" id="email" placeholder="Personiv Email Address" />
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label required">Role :</label>
                                        <div class="controls">
                                            <select id="role" class="span11" name="role">
                                                <option value="" selected>Select Here</option>
                                                <option value="agent">Agent</option>
                                                <option value="admin">Admin</option>
                                            </select>
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
                                <h5>Lists of Users</h5>
                            </div>
                            <div class="widget-content nopadding">
                                <table class="table table-bordered" id="tbl_users">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Profile</th>
                                            <th>Employee ID</th>
                                            <th>Employee Name</th>
                                            <th>Email Address</th>
                                            <th>Role</th>
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
<script src="{{asset('scripts/admin/users.js')}}"></script>
@endsection