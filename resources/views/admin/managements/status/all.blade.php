@extends('layouts.layout')
@section('content')
<div class="container-fluid">
    <div class="row-fluid">
        <div class="widget-box">
            <div class="widget-title"><span class="icon"><i class="icon-tasks"></i></span>
                <h5>Status</h5>
            </div>
            <div class="widget-content">
                <div class="row-fluid">
                    <div class="span4">
                        <div class="widget-box">
                            <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
                                <h5>New Entry</h5>
                            </div>
                            <div class="widget-content nopadding">
                                <form class="form-horizontal" method="post" id="form_status" novalidate="novalidate">
                                    @csrf
                                    <input type="hidden" id="edit_id" name="status_id">
                                    <div class="control-group">
                                        <label class="control-label required">Name of Status</label>
                                        <div class="controls">
                                            <input type="text" class="span11" name="name" id="status_name" placeholder="Name of Status" />
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
                                <h5>Lists of Status</h5>
                            </div>
                            <div class="widget-content nopadding">
                                <table class="table table-bordered" id="tbl_status">
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
<script src="{{asset('scripts/admin/status.js')}}"></script>
@endsection