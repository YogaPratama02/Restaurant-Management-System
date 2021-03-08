<script type="text/javascript" language="javascript" src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>
{{-- @livewireScripts --}}
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.2/dist/jquery.validate.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js" integrity="sha256-5YmaxAwMjIpMrVlK84Y/+NjCpKnFYa8bWWBbUHSBGfU=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script> --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>

<!-- jQuery -->
{{-- <script src="{{asset('/dashboard/plugins/jquery/jquery.min.js')}}"></script> --}}
{{-- <script src="{{asset('/js/app1.js')}}"></script>  --}}
<!-- jQuery UI 1.11.4 -->
{{-- <script src="{{asset('/dashboard/plugins/jquery-ui/jquery-ui.min.js')}}"></script> --}}
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
{{-- <script>
  $.widget.bridge('uibutton', $.ui.button)
</script> --}}
<!-- Bootstrap 4 -->
<script src="{{asset('/dashboard/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<!-- ChartJS -->
<script src="{{asset('/dashboard/plugins/chart.js/Chart.min.js')}}"></script>
<!-- Sparkline -->
<script src="{{asset('/dashboard/plugins/sparklines/sparkline.js')}} "></script>
<!-- JQVMap -->
{{-- <script src="{{asset('/dashboard/plugins/jqvmap/jquery.vmap.min.js')}}"></script> --}}
{{-- <script src="{{asset('/dashboard/plugins/jqvmap/maps/jquery.vmap.usa.js')}}"></script> --}}
<!-- jQuery Knob Chart -->
{{-- <script src="{{asset('/dashboard/plugins/jquery-knob/jquery.knob.min.js')}}"></script> --}}
<!-- daterangepicker -->
<script src="{{asset('/dashboard/plugins/moment/moment.min.js')}}"></script>
<script src="{{asset('/dashboard/plugins/daterangepicker/daterangepicker.js')}}"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="{{asset('/dashboard/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js')}}"></script>
<!-- Summernote -->
<script src="{{asset('/dashboard/plugins/summernote/summernote-bs4.min.js')}} "></script>
<!-- overlayScrollbars -->
<script src="{{asset('/dashboard/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js')}} "></script>
{{-- DataTables --}}
{{-- <script src="{{asset('/dashboard/plugins/datatables/jquery.dataTables.min.js')}}"></script> --}}
{{-- DataTables Bootstrap4 --}}
<script src="{{asset('/dashboard/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
<!-- AdminLTE App -->
<script src="{{asset('/dashboard/dist/js/adminlte.js')}}"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="{{asset('/dashboard/dist/js/pages/dashboard.js')}}"></script>
<!-- AdminLTE for demo purposes -->
<script src="{{asset('/dashboard/dist/js/demo.js')}}"></script>

@yield('charts')





