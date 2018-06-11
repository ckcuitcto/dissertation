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
                <li class="breadcrumb-item"> Chi tiết tin</li>
            </ul>
        </div>
        <div class="row">
            <div class="col-md-12">
                
                <div class="tile">          
                    <div class="jumbotron">
                        <h2 align="center">{{ $news->title}}</h2>              
                        <p style="color:tomato;text-transform:uppercase;white-space:pre">{{$news->Staff->user_id}}</p>
                        <p>{{$news->Faculty->name}} &nbsp; <i class="fa fa-clock-o" aria-hidden="true"></i> {{$news->created_at}}</p>
                        <hr class="my-4">                        
                        <p>{!! $news->content !!}</p>
                        <p align="right">Trường đại học Công nghệ Sài Gòn</p>
                    </div>                       
                </div>
            </div>
        </div>
    </main>
@endsection