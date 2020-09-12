<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="x-ua-compatible" content="ie=edge">

    @if($pageTitle!="")
        <title>{{$pageTitle}}</title>
    @else
        <title>Dashboard</title>
    @endif

    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">

    <!-- You can add your view's css files in this section 'css', which need to load in head. Apply it from your view file-->
    @yield('css')

    <script src="{{ asset('js/app.js') }}"></script>

    <!-- You can add your view's javascript files in this section 'js', which need to load in head. Apply it from your view file-->
    @yield('js')
</head>
<body class="sidebar-mini layout-fixed text-sm">
<!-- You can add your view's javascript files in this section 'js_header', which need to load in header. Apply it from your view file-->
@yield('js_header')
<div class="wrapper">

    <!-- Navbar -->
@include('layouts.topnavi')
<!-- /.navbar -->

    <!-- Main Sidebar Container -->
@include('layouts.leftmenu')
<!-- /.Main Sidebar Container -->

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper mt-2">
        <section class="content">
            @yield('content')
        </section>
    </div>
    <!-- /.Content-wrapper -->

    <!-- Control Sidebar -->
@include('layouts.controlsidebar')
<!-- /.control-sidebar -->

    <!-- Main Footer -->
@include('layouts.footer')
<!-- /.Main Footer -->

</div>
<!-- ./wrapper -->

<!-- You can add your view's javascript files in this section 'js_footer', which need to load in footer. Apply it from your view file-->
@yield('js_footer')
</body>
</html>
