<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {{--<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />--}}
    <!-- Main CSS-->
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('template/css/main.css') }}">
    {{--<link href="{{ asset('css/font-css.css') }}" rel="stylesheet">--}}
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('template/css/font-awesome.min.css') }}">
    <!-- Font-icon css-->
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.0.3/css/buttons.dataTables.min.css">

{{--    <link href="{{ URL::asset('css/bootstrap-datepicker.css') }}" rel="stylesheet"/>--}}
    <link rel="shortcut icon" href="{{ url('icon/logoSTU.ico')}}">
    <title>STU - @yield('title')</title>
</head>
<body class="app sidebar-mini rtl">
@section('header')
@show

@section('menuLeft')
@show
<div id="ajax_loader"></div>
@yield('content')

@section('javascript')
@show

@section('sub-javascript')
@show

</body>
</html>