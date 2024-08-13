<script src="{{asset('themes/js/3.7.1.jquery.min.js')}}"></script>
<script src="{{asset('themes/js/jquery.ui.custom.js')}}"></script>
<script src="{{asset('themes/js/bootstrap.min.js')}}"></script>
<script src="{{asset('themes/js/axios.min.js')}}"></script>
<script src="{{asset('themes/js/jquery.validate.js')}}"></script>
<script src="{{asset('themes/js/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('themes/js/toastr.js')}}"></script>
<script src="{{asset('addons/dist/js/cxdialog.js')}}"></script>
<script src="{{asset('vendors/plugins/jquery-datatable/extensions/export/dataTables.buttons.min.js')}}"></script>
<script src="{{asset('vendors/plugins/jquery-datatable/extensions/export/jszip.min.js')}}"></script>
<script src="{{asset('vendors/plugins/jquery-datatable/extensions/export/buttons.html5.min.js')}}"></script>
<script src="{{asset('js/global.js')}}"></script>
<script>
    var APP_URL = {!! json_encode(url('/')) !!}
    // Get the CSRF token from the meta tag
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    // Configure Axios
    axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
    axios.defaults.headers.common['X-CSRF-TOKEN'] = csrfToken;
</script>
