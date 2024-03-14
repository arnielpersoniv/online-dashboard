@extends('layouts.layout')
@section('css')
<link rel="stylesheet" href="{{asset('css/blank.css')}}" />
@endsection
@section('content')
<div class="container-fluid">
    <h1>{{$user->name}}</h1>
    <div class="row-fluid">
    </div>
    <hr>
</div>
@endsection