@extends('layouts.layout')
@section('css')
<link rel="stylesheet" href="{{asset('themes/css/select2.min.css')}}" />
<link rel="stylesheet" href="{{asset('css/admin/dashboard.css')}}" />
@endsection
@section('content')
<div class="container-fluid">
    <div class="row-fluid">
        <div class="chat-message well div-width">
            <button class="btn btn-success" id="btn_filter"><i class="fa fa-filter"></i> Filter</button>
            <span class="input-box">
                <div class="row">
                    <div class="span6">
                        <select class="margin" name="" id="slct_filter">
                            <option value="daily">Daily</option>
                            <option value="weekly">Weekly</option>
                            <option value="monthly">Monthly</option>
                            <option value="yearly">Yearly</option>
                        </select>
                    </div>
                    <div class="span5 margin" id="div_filter">
                    </div>
                </div>
            </span>
        </div>
    </div>
    <div class="quick-actions_homepage">
        <ul class="quick-actions">
            <li> <a href="#">
                    <h3><i class="icon-user"></i> {{$user->name}} </h3>
                </a> </li>
            <span id="total_category">
            </span>
            <span id="totalTask">
            </span>
        </ul>
    </div>
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
                                <h5>Entry</h5>
                            </div>
                            <div class="widget-content nopadding">
                                <form class="form-horizontal" method="post" id="form_activity" novalidate="novalidate">
                                    @csrf
                                    <input type="hidden" id="edit_id" name="activity_id">
                                    <div class="control-group">
                                        <label class="control-label required">Order No.</label>
                                        <div class="controls">
                                            <input type="text" class="span11" name="order_no" id="order_no" placeholder="Order No." autocomplete="off"/>
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label required">Account No.</label>
                                        <div class="controls">
                                            <input type="text" class="span11" name="account_no" id="account_no" placeholder="Account No." autocomplete="off"/>
                                        </div>
                                    </div>

                                    <div class="control-group">
                                        <label class="control-label required">Category</label>
                                        <div class="controls">
                                            <select class="select2" id="category_id" name="category_id">
                                            </select>
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label required">Task</label>
                                        <div class="controls">
                                            <select class="select2" id="task_id" name="task_id">
                                            </select>
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label">Released By :</label>
                                        <div class="controls">
                                            <input type="text" class="span11" value="{{$user->name}}" readonly>
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label required">Status</label>
                                        <div class="controls">
                                            <select class="span11 select2" id="status" name="status">
                                                <option value="" selected disabled>Select Here</option>
                                                <option value="released">Released</option>
                                                <!-- <option value="hold" disabled>Hold</option>
                                                <option value="completed" disabled>Completed</option> -->
                                            </select>
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label">Time Start :</label>
                                        <div class="controls">
                                            <input type="text" class="span11" value="{{date('h:i:s')}}" readonly>
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
                                <h5>Lists of Tasks</h5>
                            </div>
                            <div class="widget-content nopadding">
                                <table class="table table-bordered" id="tbl_activity">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Date</th>
                                            <th>Order/Case No.</th>
                                            <th>Account No.</th>
                                            <th>Category</th>
                                            <th>Tasks</th>
                                            <th>Released By</th>
                                            <th>Status</th>
                                            <th>Time Spent</th>
                                            <th></th>
                                            <th></th>
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
<!-- modal open -->
<div id="modal_hold" class="modal fade" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-header">
        <button data-dismiss="modal" class="close" type="button">×</button>
        <h3>Your are trying to your task. <strong id="str_account"></strong></h3>
    </div>
    <div class="modal-body">
        <div class="widget-content nopadding">
            <form method="post" class="form-horizontal" id="form_hold" novalidate="novalidate">
                <input type="hidden" id="hold_id" name="hold_id">
                <div class="control-group">
                    <label class="control-label required">Reason for Hold</label>
                    <div class="controls">
                        <input type="text" name="hold_reason" id="hold_reason" placeholder="Reason for hold" />
                    </div>
                </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-primary" id="btn_pause">Save</button>
        <button data-dismiss="modal" class="btn">Close</button>
    </div>
    </form>
</div>
<!-- modal close -->
@endsection
@section('js')
<script src="{{asset('themes/js/select2.min.js')}}"></script>
<script src="{{asset('scripts/users/activity.js')}}"></script>
@endsection