<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Module Slo</title>
    <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}">
    <!-- Google Font: Source Sans Pro -->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
    {{-- Laravel Mix - CSS File --}}
    {{--
    <link rel="stylesheet" href="{{ mix('css/slo.css') }}">
    --}}
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body class="sidebar-mini layout-fixed text-sm">

<div class="wrapper">
    <!-- Navbar -->
    @include('layouts.topnavi')
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    @include('layouts.leftmenu')
    <!-- /.Main Sidebar Container -->

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">


        <script src="{{ asset('js/app.js') }}"></script>
        <script src="{{ asset('js/slo.js') }}"></script>
        @yield('content')

        {{-- Laravel Mix - JS File --}}
        {{--
        <script src="{{ mix('js/slo.js') }}"></script>
        --}}
    </div>

    @include('layouts.controlsidebar')
    <!-- /.control-sidebar -->

    <!-- Main Footer -->
    @include('layouts.footer')
    <!-- /.Main Footer -->

</div>

<script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
<!-- Bootstrap 4 -->
<script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('dist/js/adminlte.min.js') }}"></script>
</body>
</html>
