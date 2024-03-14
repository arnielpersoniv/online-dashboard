@extends('layouts.layout')
@section('css')
<link rel="stylesheet" href="{{asset('css/blank.css')}}" />
@endsection
@section('content')
<div class="container-fluid">
    <div class="quick-actions_homepage">
        <ul class="quick-actions">
            <li> <a href="#"> <i class="icon-user"></i> {{$user}} </a> </li>
            <li> <a href="#"> <i class="icon-dashboard"></i> Voice </a> </li>
            <li> <a href="#"> <i class="icon-mail"></i> Voice Mail</a> </li>
            <li> <a href="#"> <i class="icon-book"></i> Credit Order </a> </li>
            <li> <a href="#"> <i class="icon-mail"></i> Email Fax </a> </li>
            <li> <a href="#"> <i class="icon-cabinet"></i> Chats </a> </li>
            <li> <a href="#"> <i class="icon-dashboard"></i> <strong>155</strong> Total </a> </li>
        </ul>
    </div>

    <div class="row-fluid">
        <div class="widget-box">
            <div class="widget-title"><span class="icon"><i class="icon-tasks"></i></span>
                <h5>Tasks</h5>
                <div class="buttons"><a href="#" class="btn btn-mini btn-success"><i class="icon-refresh"></i> Update stats</a></div>
            </div>
            <div class="widget-content">
                <div class="row-fluid">
                    <div class="span4">
                        <div class="widget-box">
                            <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
                                <h5>Entry</h5>
                            </div>
                            <div class="widget-content nopadding">
                                <form action="#" method="get" class="form-horizontal">
                                    <div class="control-group">
                                        <label class="control-label">Order No. :</label>
                                        <div class="controls">
                                            <input type="text" class="span11" placeholder="Order No." />
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label">Account No. :</label>
                                        <div class="controls">
                                            <input type="text" class="span11" placeholder="Account No." />
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label">Status</label>
                                        <div class="controls">
                                            <select>
                                                <option>First option</option>
                                                <option>Second option</option>
                                                <option>Third option</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label">Category :</label>
                                        <div class="controls">
                                            <select>
                                                <option>First option</option>
                                                <option>Second option</option>
                                                <option>Third option</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label">Task :</label>
                                        <div class="controls">
                                            <select>
                                                <option>First option</option>
                                                <option>Second option</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-actions">
                                        <button type="submit" class="btn btn-success">Save</button>
                                        <button type="button" class="btn btn-danger">Cancel</button>
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
                                <table class="table table-bordered data-table">
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
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="gradeX">
                                            <td>Trident</td>
                                            <td>Internet Explorer 4.0</td>
                                            <td>Win 95+</td>
                                            <td class="center">4</td>
                                            <td>Win 95+</td>
                                            <td>Win 95+</td>
                                            <td>Win 95+</td>
                                            <td>Win 95+</td>
                                            <td>Win 95+</td>
                                        </tr>
                                        <tr class="gradeC">
                                            <td>Trident</td>
                                            <td>Internet Explorer 5.0</td>
                                            <td>Win 95+</td>
                                            <td class="center">5</td>
                                            <td>Trident</td>
                                            <td>Trident</td>
                                            <td>Trident</td>
                                            <td>Trident</td>
                                            <td>Trident</td>
                                        </tr>
                                        <tr class="gradeA">
                                            <td>Trident</td>
                                            <td>Internet
                                                Explorer 5.5</td>
                                            <td>Win 95+</td>
                                            <td class="center">5.5</td>
                                            <td>Trident</td>
                                            <td>Trident</td>
                                            <td>Trident</td>
                                            <td>Trident</td>
                                            <td>Trident</td>
                                        </tr>
                                        <tr class="gradeA">
                                            <td>Trident</td>
                                            <td>Internet
                                                Explorer 6</td>
                                            <td>Win 98+</td>
                                            <td class="center">6</td>
                                            <td>Trident</td>
                                            <td>Trident</td>
                                            <td>Trident</td>
                                            <td>Trident</td>
                                            <td>Trident</td>
                                        </tr>
                                        <tr class="gradeA">
                                            <td>Trident</td>
                                            <td>Internet Explorer 7</td>
                                            <td>Win XP SP2+</td>
                                            <td class="center">7</td>
                                            <td>Trident</td>
                                            <td>Trident</td>
                                            <td>Trident</td>
                                            <td>Trident</td>
                                            <td>Trident</td>
                                        </tr>
                                    </tbody>
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