@extends('layouts.layout')
@section('css')
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
        <div class="quick-actions_homepage">
            <ul class="quick-actions">
                <li> <a href="#">
                        <h3> RUNNING DATA MONITORING </h3>
                    </a> </li>
            </ul>
        </div>
        <br>
        <button class="btn btn-success" id="btn_export"><i class="fa fa-file-excel-o"></i> Export</button>
    </div>
    <div class="row-fluid">
        <div class="span6">
            <div class="widget-box">
                <div class="widget-title">
                    <span class="icon"><i class="icon-th"></i></span>
                    <h5></h5>
                    <div class="buttons"><a href="#" class="btn btn-mini btn-success btn_hide" id="1"><i class="fa fa-eye"></i> Hide/Show</a></div>
                </div>
                <h5 class="h5-label" id="label_agent"></h5>
                <div class="widget-content nopadding" id="hideTable1">
                    <table class="table table-bordered data-table" id="tbl_agent">
                        <thead>
                            <tr id="thead_agent"></tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="span6">
            <div class="widget-box">
                <div class="widget-title">
                    <span class="icon"><i class="icon-th"></i></span>
                    <h5></h5>
                    <div class="buttons"><a href="#" class="btn btn-mini btn-success btn_hide" id="3"><i class="fa fa-eye"></i> Hide/Show</a></div>
                </div>
                <h5 class="h5-label" id="label_agent2"></b></h5>
                <div class="widget-content nopadding" id="hideTable3">
                    <table class="table table-bordered data-table" id="tbl_agenttask">
                        <thead>
                            <tr id="thead_agenttask"></tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="row-fluid">
        <div class="span6">
            <div class="widget-box">
                <div class="widget-title">
                    <span class="icon"><i class="icon-th"></i></span>
                    <h5></h5>
                    <div class="buttons"><a href="#" class="btn btn-mini btn-success btn_hide" id="2"><i class="fa fa-eye"></i> Hide/Show</a></div>
                </div>
                <h5 class="h5-label" id="label_task"></h5>
                <div class="widget-content nopadding" id="hideTable2">
                    <table class="table table-bordered data-table" id="tbl_task">
                        <thead>
                            <tr id="thead_task"></tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="span6">
            <div class="widget-box">
                <div class="widget-title">
                    <span class="icon"><i class="icon-th"></i></span>
                    <h5></h5>
                    <div class="buttons"><a href="#" class="btn btn-mini btn-success btn_hide" id="2"><i class="fa fa-eye"></i> Hide/Show</a></div>
                </div>
                <h5 class="h5-label"><b id="label_task2"></b></h5>
                <div class="widget-content nopadding" id="hideTable4">
                    <table class="table table-bordered data-table" id="tbl_task2">
                        <thead>
                            <tr id="thead_task2"></tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <hr>
</div>
@endsection
@section('js')
<script type="text/javascript" src="{{asset('vendors/plugins/jquery.tableTotal.js')}}"></script>
<script src="{{asset('scripts/admin/dashboard/wip.js')}}"></script>
@endsection