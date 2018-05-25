@extends('layouts.default')

@section('content')
    <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class="fa fa-laptop"></i> Quản lý tin tức, sự kiện</h1>
                <p>Trường Đại học Công nghệ Sài Gòn</p>
            </div>
            <ul class="app-breadcrumb breadcrumb">
                <li class="breadcrumb-item"><a href="{{route('home')}}"><i class="fa fa-home fa-lg"></i></a></li>
                <li class="breadcrumb-item"><a href="#"> Quản lý tin tức, sự kiện</a></li>
            </ul>
        </div>
        <div class="row">
            <div class="col-md-12">
                
                <div class="tile">          
                    <div class="jumbotron">
                        <h1 class="display-4" align="center">{{ $news->title}}</h1>
                        <hr class="my-4">
                        {{-- <p class="lead">
                            <img src="http://placeimg.com/1180/400/any">
                        </p> --}}
                        {{-- <hr class="my-4"> --}}
                        <p>{!! $news->content !!}</p>
                    </div>                       
                </div>
            </div>
        </div>
    </main>
@endsection