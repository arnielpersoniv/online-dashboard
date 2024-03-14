<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="Content-Security-Policy" content="default-src 'self'">
    <title>CS - Online Dashboard</title>
    <!-- Favicon icon -->
    <link rel="icon" href="{{asset('favicon.ico')}}" type="image/x-icon">
    <link rel="stylesheet" href="{{asset('css/login/bootstrap.min.css')}}" />
    <link rel="stylesheet" href="{{asset('css/login/login-10.css')}}" />
</head>

<body>
    <!-- Login 10 - Bootstrap Brain Component -->
    <section class="py-5 py-md-8 py-xl-10">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 col-sm-10 col-md-8 col-lg-6 col-xl-5 col-xxl-4">
                    <div class="mb-2">
                        <div class="text-center mt-5">
                            <a href="#!">
                                <img src="./themes/img/personiv-logo.png" alt="BootstrapBrain Logo">
                            </a>
                        </div>
                    </div>
                    <div class="card border border-light-subtle rounded-4">
                        <div class="card-body p-3 p-md-4 p-xl-5">
                            <form action="{{route('connect')}}" id="form_login">
                                <img src="./themes/img/maylogo.png" alt="BootstrapBrain Logo" height="100px">
                                <div class="row gy-3 overflow-hidden">
                                    <div class="col-12">
                                        <div class="d-grid">
                                            <button class="btn btn-primary btn-lg" id="btn_login" type="button">Single Sign-On</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script src="{{asset('themes/js/3.7.1.jquery.min.js')}}"></script>
    <script src="{{asset('js/login.js')}}"></script>
</body>
</html>