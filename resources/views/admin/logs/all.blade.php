@extends('layouts.layout')
@section('css')
<link rel="stylesheet" href="{{asset('css/admin/dashboard.css')}}" />
@endsection
@section('content')
<div class="container-fluid">
    <div class="row-fluid">
        <div class="widget-box">
            <div class="widget-content">
                <div class="row-fluid">
                    <div class="span12">
                        <!-- <button><i class="icon-download"></i> Export</button> -->
                        <div class="widget-box">
                            <div class="widget-title">
                                <span class="icon"><i class="icon-th"></i></span>
                                <h5>User's Activities</h5>
                                <!-- <div class="buttons">Search: <input type="text" id="searchbox"></div> -->
                            </div>
                            <div class="widget-content nopadding">
                                <table class="table table-bordered" id="tbl_logs">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Name</th>
                                            <th>Module</th>
                                            <th>Action</th>
                                            <th>Status</th>
                                            <th>Ip Address</th>
                                            <th>Date Created</th>
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
<script src="{{asset('scripts/admin/logs.js')}}"></script>
@endsection