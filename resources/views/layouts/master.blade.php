<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Main CSS-->
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('template/css/main.css') }}">
    <!-- Font-icon css-->
    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="{{ URL::asset('css/bootstrap-datepicker.css') }}" rel="stylesheet"/>
{{--    <link rel="stylesheet" type="text/css" href="{{ asset('template/css/font-awesome.min.css') }}">--}}
    <title>STU - @yield('title')</title>
</head>
<body class="app sidebar-mini rtl">
@section('header')
@show

@section('menuLeft')
@show

@yield('content')

@section('javascript')

@show

@section('sub-javascript')
@show

</body>
</html>