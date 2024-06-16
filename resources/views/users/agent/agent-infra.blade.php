@extends('layouts.layout')
@section('css')
<link rel="stylesheet" href="{{asset('themes/css/select2.min.css')}}" />
<link rel="stylesheet" href="{{asset('css/admin/dashboard.css')}}" />
@endsection
@section('content')
<div class="container-fluid">
    <div class="row-fluid">
        <div class="chat-message well div-width">
            <button class="btn btn-success tip-top" id="btn_filter" data-original-title="Filter Data"><i class="fa fa-filter"></i> Filter</button>
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
            <li>
                <a href="#">
                    <h3>
                        @if(Auth::user()->profile != null)
                        <img src="{{url('storage/profiles')}}/{{Auth::user()->profile}}" alt="profile" class="display_profile"/>
                        @else
                        <img src="{{url('themes/images/faces/avatar.png')}}" alt="profile" class="display_profile"/>
                        @endif
                        <br>
                        {{$user->name}} </h3>
                </a>
            </li>
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
                                <form class="form-horizontal" method="post" id="form_task" novalidate="novalidate">
                                    @csrf
                                    <input type="hidden" id="edit_id" name="edit_id">
                                    <div class="control-group">
                                        <label class="control-label required">LID No.</label>
                                        <div class="controls">
                                            <input type="number" class="span11" name="lid_no" id="lid_no" placeholder="Search or Input LID No." autocomplete="off" />
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label required">Category</label>
                                        <div class="controls">
                                            <select class="select2" id="category" name="category">
                                                <option value="" selected>Select Here</option>
                                                <option value="Outbound">Outbound</option>
                                                <option value="Inbound">Inbound</option>
                                                <option value="Text">Text</option>
                                                <option value="E-mail">E-mail</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label required">Task</label>
                                        <div class="controls">
                                            <select class="select2" id="task" name="task">
                                                <option value="" selected>Select Here</option>
                                                <option value="Schedule">Schedule</option>
                                                <option value="Rebook">Rebook</option>
                                                <option value="Follow-up Tech">Follow-up Tech</option>
                                                <option value="BOB Update">BOB Update</option>
                                                <option value="CMS Address Update">CMS ADDRESS Update</option>
                                                <option value="CMS Install Update">CMS Install Update</option>
                                                <option value="Zuper Ticket">Zuper Ticket</option>
                                                <option value="Booking for Appointment">Booking for Appointment</option>
                                                <option value="Cancellation">Cancellation</option>
                                                <option value="ADHOC">ADHOC</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="control-group" id="div_adhoc">
                                        <label class="control-label required">ADHOC</label>
                                        <div class="controls">
                                            <input type="text" class="span11" name="adhoc" id="adhoc" required>
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label required">Status</label>
                                        <div class="controls">
                                            <select class="select2" id="status" name="status">
                                                <option value="" selected>Select Here</option>
                                                <option value="Pending">Pending</option>
                                                <option value="Done">Done</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label" id="label_startend"></label>
                                        <div class="controls">
                                            <input type="text" class="span11" value="{{date('g:i:s a')}}" readonly>
                                        </div>
                                    </div>
                                    <div class="form-actions">
                                        <button type="submit" id="btn_save" class="btn btn-success tip-top" data-original-title="Click to submit">Submit</button>
                                        <button type="reset" id="btn_cancel" class="btn btn-danger tip-top" data-original-title="Clear entry">Cancel</button>
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
                                <table class="table table-bordered" id="tbl_agenttasks">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Date</th>
                                            <th>LID No.</th>
                                            <th>Category</th>
                                            <th>Tasks</th>
                                            <th>Status</th>
                                            <th>Adhoc</th>
                                            <th>Time Spent</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                                <div class="overlay div-spinner" id="loading"><i class="fa fa-spinner fa-pulse i-spinner"></i></div>
                            </div>
                        </div>
                    </div>
                    <div class="span10">
                        <button class="btn btn-success tip-top" data-original-title="Click to download" id="btn_export"><i class="fa fa-file-excel-o"></i> Export</button>
                        <div class="widget-box">
                            <div class="widget-title">
                                <span class="icon"><i class="icon-th"></i></span>
                                <h5>RUNNING PERFORMANCE DATA - <b id="b_label"></b></h5>
                            </div>
                            <div class="widget-content nopadding">
                                <table class="table table-bordered" id="tbl_agentperformance">
                                    <thead>
                                        <tr id="thead_agentperformance"></tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                                <!-- <div class="overlay div-spinner" id="loading"><i class="fa fa-spinner fa-pulse i-spinner"></i></div> -->
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
<!-- <div id="modal_hold" class="modal fade" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
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
</div> -->
<!-- modal close -->
@endsection
@section('js')
<script src="{{asset('themes/js/select2.min.js')}}"></script>
<script type="text/javascript" src="{{asset('vendors/plugins/jquery.tableTotal.js')}}"></script>
<script src="{{asset('scripts/users/agent-task.js')}}"></script>
@endsection