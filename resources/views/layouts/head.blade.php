
<!DOCTYPE html>
<html lang="en">
{{--<?php header("Content-Security-Policy: default-src 'self'; img-src 'self' data:;"); ?> --}}
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Online Dashboard</title>
    <!-- Favicon icon -->
    <link rel="icon" href="{{asset('favicon.png')}}" type="image/x-icon">
    @include('layouts.main_style')
</head>