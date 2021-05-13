<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>@yield('title')</title>

  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  @livewireStyles
  {{-- Style --}}
  @stack('before-style')
  @include('includes.style')
  @stack('after-style')

</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

  {{-- Navbar --}}
  @stack('before-navbar')
  @include('includes.navbar')
  @stack('after-navbar')

  {{-- Sidebar --}}
  @stack('before-sidebar')
  @include('includes.sidebar')
  @stack('after-sidebar')

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper" style="background-color: white">
    {{-- Content --}}
    @stack('before-content')
    @yield('content')
    @stack('after-content')
  </div>
  <!-- /.content-wrapper -->

  @include('layouts.modal')

  {{-- Footer --}}
  @stack('before-footer')
  @include('includes.footer')
  @stack('after-footer')

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

{{-- Script --}}
@stack('before-script')
@include('includes.script')
@stack('after-script')
{{-- @livewire('counter') --}}
@livewireScripts
</body>
</html>
