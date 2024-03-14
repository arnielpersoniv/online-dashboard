@include('layouts.head')
    <!--custom css-->
    @yield('css')
    <!--close-custom css-->
<body>

    <!--Header-part-->
    @include('layouts.header')
    <!--close-Header-part-->

    <!--top-Header-messaages-->
    @include('layouts.top_header')
    <!--close-top-Header-messaages-->

    <!--top-Header-menu-->
    @include('layouts.top_header_menu')
    <!--close-top-Header-menu-->

    <!--sidebar-menu-->
    @include('layouts.sidebar_menu')
    <!--close-sidebar-menu-->


    <div id="content">
        <div id="content-header">
            <div id="breadcrumb"> 
                <a href="{{url('/home')}}" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a>
                <span class="currentpage"></span>
            </div>
        </div>
        @yield('content')
    </div>
    </div>
    </div>
    <button class="btn btn-info" id="myBtn" title="Go to top">Back to Top</button>
    <!--footer-->
    @include('layouts.footer')
    <!--close-footer-->

    <!--javascript-->
    @include('layouts.main_script')
    <!--end-javascript-->

    @yield('js')
</body>

</html>