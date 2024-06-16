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
                            <option value="all">All</option>
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
        <div class="quick-actions_homepage">
            <ul class="quick-actions">
                <li> <a href="#">
                        <h3> AGENT DATA MONITORING </h3>
                    </a> </li>
            </ul>
        </div>
        <br>
        <button class="btn btn-success tip-top" data-original-title="Click to download" id="btn_export"><i class="fa fa-file-excel-o"></i> Export</button>
        <div class="widget-box" id="div_daily">
            <div class="widget-title">
                <span class="icon"><i class="icon-th"></i></span>
                <h5 id="txt_daily"></h5>
                <div class="buttons"><a href="#" class="btn btn-mini btn-success btn_hide tip-top" data-original-title="Click to Hide/Show" id="1"><i class="fa fa-eye"></i> Hide/Show</a></div>
            </div>
            <h5 class="h5-label">DAILY ACTIVITIES COUNT</h5>
            <div class="widget-content nopadding" id="hideTable1">
                <table class="table table-bordered data-table" id="tbl_daily">
                    <thead>
                        <tr id="thead_daily"></tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="row-fluid">
        <div class="widget-box" id="div_weekly">
            <div class="widget-title">
                <span class="icon"><i class="icon-th"></i></span>
                <h5 id="txt_weekly"></h5>
                <div class="buttons"><a href="#" class="btn btn-mini btn-success btn_hide tip-top" data-original-title="Click to Hide/Show" id="2"><i class="fa fa-eye"></i> Hide/Show</a></div>
            </div>
            <h5 class="h5-label">WEEKLY SUMMARY - <b id="label_weekly"></b></h5>
            <div class="widget-content nopadding" id="hideTable2">
                <table class="table table-bordered data-table" id="tbl_weekly">
                    <thead>
                        <tr id="thead_weekly"></tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="row-fluid">
        <div class="widget-box" id="div_monthly">
            <div class="widget-title">
                <span class="icon"><i class="icon-th"></i></span>
                <h5 id="txt_monthly"></h5>
                <div class="buttons"><a href="#" class="btn btn-mini btn-success btn_hide tip-top" data-original-title="Click to Hide/Show" id="3"><i class="fa fa-eye"></i> Hide/Show</a></div>
            </div>
            <h5 class="h5-label">MONTHLY ACTIVITIES COUNT</h5>
            <div class="widget-content nopadding" id="hideTable3">
                <table class="table table-bordered data-table" id="tbl_monthly">
                    <thead>
                        <tr id="thead_monthly"></tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="row-fluid">
        <div class="widget-box" id="div_yearly">
            <div class="widget-title">
                <span class="icon"><i class="icon-th"></i></span>
                <h5 id="txt_yearly"></h5>
                <div class="buttons"><a href="#" class="btn btn-mini btn-success btn_hide tip-top" data-original-title="Click to Hide/Show" id="4"><i class="fa fa-eye"></i> Hide/Show</a></div>
            </div>
            <h5 class="h5-label">YTD ACTIVITIES COUNT</h5>
            <div class="widget-content nopadding" id="hideTable4">
                <table class="table table-bordered data-table" id="tbl_yearly">
                    <thead>
                        <tr id="thead_yearly"></tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
    <hr>
</div>
@endsection
@section('js')
<script type="text/javascript" src="{{asset('vendors/plugins/jquery.tableTotal.js')}}"></script>
<script src="{{asset('scripts/admin/dashboard/agent.js')}}"></script>
@endsection