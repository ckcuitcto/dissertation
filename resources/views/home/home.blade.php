@extends('layouts.default')
@section('content')
    <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class="fa fa-home fa-lg" aria-hidden="true"></i> Trang chủ</h1>
                <p>Trường Đại học Công nghệ Sài Gòn</p>
            </div>
            <ul class="app-breadcrumb breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home')}}"><i class="fa fa-home fa-lg"></i></a></li>
                <li class="breadcrumb-item">Trang chủ</li>
            </ul>
        </div>

        <div class="row">
            <div class="col-md-6 col-lg-3">
            <a href="http://daotao1.stu.edu.vn/Default.aspx?page=thoikhoabieu" style="text-decoration: none;">
                    <div class="widget-small primary coloured-icon"><i class="icon fa fa-calendar-o fa-3x"></i>
                        <div class="info">
                            <h4>Thời Khóa Biểu</h4>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-6 col-lg-3">
                <a href="http://daotao1.stu.edu.vn/Default.aspx?page=xemdiemthi" style="text-decoration: none;">
                    <div class="widget-small info coloured-icon"><i class="icon fa fa-thumbs-o-up fa-3x"></i>
                        <div class="info">
                            <h4>Điểm Học Kỳ</h4>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-6 col-lg-3">
                <a href="http://daotao1.stu.edu.vn/Default.aspx?page=dkmonhoc" style="text-decoration: none;">
                    <div class="widget-small warning coloured-icon"><i class="icon fa fa-calendar fa-3x"></i>
                        <div class="info">
                            <h4>Đăng ký môn học</h4>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-6 col-lg-3">
                <a href="http://daotao1.stu.edu.vn/Default.aspx?page=xemhocphi" style="text-decoration: none;">
                    <div class="widget-small danger coloured-icon"><i class="icon fa fa-money fa-3x"></i>
                        <div class="info">
                            <h4>Học Phí</h4>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <div class="row">
            @if(!empty($newsList))
            <div class="col-md-6">
                <div class="tile">
                    <h3 class="tile-title"><i class="fa fa-bell" aria-hidden="true"></i> &nbsp;Tin tức</h3>
                    @foreach($newsList as $news)
                    <div class="card mb-2" style="background-color:#66d6b8;color:white">
                            <div class="card-header">
                                <div>{{$news->title}}</div>
                                <a href="{{ route('news-show',[ str_slug($news->title),$news->id]) }}">
                                    <p style="float:right">Xem thêm >></p>
                                </a>
                            </div>                            
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
            @isset($timeList)
            <div class="col-md-6">
                <div class="tile">
                    <h3 class="tile-title"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> &nbsp;Thời gian đánh giá điểm rèn luyện</h3>                   
                    <div class="card mb-3">
                        <div class="card-header">
                            <div>Đánh giá điểm rèn luyện học kỳ {{$timeList->term}} năm học {{$timeList->year_to}}</div>
                        </div>
                        <div class="card-body">
                          {{-- <h5 class="card-title">Warning card title</h5> --}}
                            <p class="card-text"> {{$timeList->date_start_to_mark}} - {{$timeList->date_end_to_mark}}
                                Thời gian đánh giá điểm rèn luyện.
                            </p>
                            <p class="card-text"> {{$timeList->date_start_to_request_re_mark}} - {{$timeList->date_end_to_request_re_mark}}
                                Thời gian khiếu nại
                            </p>
                            <p class="card-text"> {{$timeList->date_start_to_re_mark}} - {{$timeList->date_end_to_re_mark}}
                                Thời gian chấm lại khiếu nại
                            </p>
                        </div>
                    </div>
                    
                </div>
            </div>
            @endisset
        </div>       
    </main>
@endsection
@section('sub-javascript')  
@stop