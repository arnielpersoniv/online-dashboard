@extends('layouts.layout')
@section('css')
<link rel="stylesheet" href="{{asset('css/admin/dashboard.css')}}" />
@endsection
@section('content')
<div class="container-fluid">
    <div class="row-fluid">
        <div class="chat-message well div-width">
            <button class="btn btn-success tip-top" data-original-title="Filter Data" id="btn_filter"><i class="fa fa-filter"></i> Filter</button>
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
                    <div class="span5 margin-date" id="div_filter">
                    </div>
                </div>
            </span>
        </div>
    </div>
    <div class="quick-actions_homepage">
        <ul class="quick-actions">
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
                    <div class="span12">
                        <div class="widget-box">
                            <div class="widget-title">
                                <span class="icon"><i class="icon-th"></i></span>
                                <h5>Lists of Tasks</h5>
                            </div>
                            <div class="widget-content nopadding">
                                <table class="table table-bordered" id="tbl_agentalltask">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Date</th>
                                            <th>LID No.</th>
                                            <th>Category</th>
                                            <th>Adhoc Category</th>
                                            <th>Tasks</th>
                                            <th>Adhoc Task</th>
                                            <th>Status</th>
                                            <th>Released By</th>
                                            <th>Time Spent</th>
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
<script src="{{asset('scripts/admin/agent_all_task.js')}}"></script>
@endsection