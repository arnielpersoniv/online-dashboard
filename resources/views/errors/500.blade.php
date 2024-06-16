<!DOCTYPE html>
<html lang="en">
<?php header("Content-Security-Policy: default-src 'self'; img-src 'self' data:;"); ?>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>500 Server Error</title>
    <!-- Favicon icon -->
    <link rel="icon" href="{{asset('favicon.ico')}}" type="image/x-icon">
    <link rel="stylesheet" href="{{asset('css/login/login-10.css')}}" />
    <link rel="stylesheet" href="{{asset('css/500.css')}}" />
</head>

<body>
<div class="container">
        <div class="error">
            <h1>500</h1>
            <h2>Internal Server Error!</h2>
            <p>Sorry, something went wrong on our end. File a ticket / Contact the administrator.</p>
        </div>
        <div class="stack-container">
            <div class="card-container">
                <div class="perspec div-style1">
                    <div class="card">
                        <div class="writing">
                            <div class="topbar">
                                <div class="red"></div>
                                <div class="yellow"></div>
                                <div class="green"></div>
                            </div>
                            <div class="code">
                                <ul>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-container">
                <div class="perspec div-style2">
                    <div class="card">
                        <div class="writing">
                            <div class="topbar">
                                <div class="red"></div>
                                <div class="yellow"></div>
                                <div class="green"></div>
                            </div>
                            <div class="code">
                                <ul>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-container">
                <div class="perspec div-style3">
                    <div class="card">
                        <div class="writing">
                            <div class="topbar">
                                <div class="red"></div>
                                <div class="yellow"></div>
                                <div class="green"></div>
                            </div>
                            <div class="code">
                                <ul>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-container">
                <div class="perspec div-style4">
                    <div class="card">
                        <div class="writing">
                            <div class="topbar">
                                <div class="red"></div>
                                <div class="yellow"></div>
                                <div class="green"></div>
                            </div>
                            <div class="code">
                                <ul>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="particles"></div>
    <script src="{{asset('themes/js/3.7.1.jquery.min.js')}}"></script>
    <script src="{{asset('js/login.js')}}"></script>
    <script src="{{asset('js/500.js')}}"></script>
</body>
</html>