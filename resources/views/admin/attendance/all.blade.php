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
                <div class="span12">
                    <input type="month" id="txt_month" class="span11" value="<?= date('Y-m') ?>">
                </div>
            </span>
        </div>
        <div class="quick-actions_homepage">
            <ul class="quick-actions">
                <li> <a href="#">
                        <h3> ATTENDANCE MONITORING </h3>
                    </a> 
                </li>
            </ul>
        </div>
        <br>
        <button class="btn btn-success tip-top" data-original-title="Click to download" id="btn_export"><i class="fa fa-file-excel-o"></i> Export</button>
    </div>
    <div class="row-fluid">
        <div class="widget-box">
            <div class="widget-title">
                <span class="icon"><i class="icon-th"></i></span>
                <h5></h5>
            </div>
            <h5 class="h5-label"></h5>
            <div class="widget-content nopadding">
                <table class="table table-bordered data-table" id="tbl_attendance">
                    <thead>
                        <tr id="thead_users"></tr>
                    </thead>
                    <tbody></tbody>
                    <tfoot>
                        <tr id="tfoot_present"></tr>
                        <tr id="tfoot_absent"></tr>
                        <tr id="tfoot_rate"></tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
    <hr>
</div>
@endsection
@section('js')
<script src="{{asset('scripts/admin/attendance.js')}}"></script>
@endsection