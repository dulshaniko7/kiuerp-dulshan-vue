@extends('layouts.master')

@section('css')
    <link href="{{ asset('academic/css/academic.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/plugins/magicsuggest/magicsuggest-min.css') }}" rel="stylesheet">
    @yield('page_css')
@endsection

@section('js')
    <script src="{{ asset('assets/js/jquery.form.min.js') }}"></script>
    <script src="{{ asset('academic/js/academic.js') }}"></script>
    <script src="{{ asset('assets/plugins/magicsuggest/magicsuggest-min.js') }}"></script>
    @yield('page_js')
@endsection

@section('content')
    @include("academic::layouts.header")
    @yield("page_content")
@stop
