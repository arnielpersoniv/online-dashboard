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
    <title>Under Maintenance</title>
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
                            <h1>Maintenance in progress!</h1>
                            <div class="row gy-3 overflow-hidden mt-10px">
                                <div>
                                    <p>Sorry for the inconvenience. We&rsquo;re performing some maintenance at the moment. If you need assistance you can always reach out us thru <strong>IT War Room</strong> or just email us at <strong><a href="mail:to">it.ph@personiv.com</a></strong>, otherwise we&rsquo;ll be back up shortly!</p>
                                    <p>&mdash; IT Programmer</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</body>

</html>