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

        <div class="row mb-4">
            <div class="col-md-12">
                <div class="tile">
                    <h3 class="tile-title"><i class="fa fa-bell" aria-hidden="true"></i> &nbsp;Thông báo</h3>
                    <div class="card-columns">
                    @foreach($newsList as $news)
                    <div class="card text-white bg-info mb-3">
                        <div class="card-header"><h5>{{$news->title}}</h5></div>
                        <div class="card-body">
                          {{-- <h5 class="card-title">Info card title</h5> --}}
                          <p class="card-text">{{$news->content}}</p>
                        </div>
                    </div>  
                    @endforeach     
                    </div>            
                </div>
            </div>
            <div class="col-md-6">
                <div class="tile">
                    <h3 class="tile-title"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> &nbsp;Thời gian đánh giá điểm rèn luyện</h3>                   
                    <div class="card text-white bg-warning mb-3">
                        <div class="card-header">
                            <div>Đánh giá điểm rèn luyện học kỳ {{$timeList->term}} năm học {{$timeList->year_to}}</div>
                        </div>
                        <div class="card-body">
                          {{-- <h5 class="card-title">Warning card title</h5> --}}
                          <p class="card-text">Ngày bắt đầu {{$timeList->date_start_to_mark}}</p>
                          <p class="card-text">Ngày kết thúc {{$timeList->date_end_to_mark}} >>><a href="#">Đánh giá ngay</a></p>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>

        {{-- <div class="row">
            <div class="col-md-6">
                <div class="tile">
                    <h3 class="tile-title">Pie Chart</h3>
                    <div class="embed-responsive embed-responsive-16by9">
                        <canvas class="embed-responsive-item" id="pieChartDemo"></canvas>
                    </div>
                </div>
            </div>
        </div> --}}
    </main>
@endsection

@section('sub-javascript')
    <!-- Page specific javascripts-->
    <script type="text/javascript" src="{{ URL::asset('template/js/plugins/chart.js') }}"></script>
    <script type="text/javascript">
        var data = {
            labels: ["January", "February", "March", "April", "May"],
            datasets: [
                {
                    label: "My First dataset",
                    fillColor: "rgba(220,220,220,0.2)",
                    strokeColor: "rgba(220,220,220,1)",
                    pointColor: "rgba(220,220,220,1)",
                    pointStrokeColor: "#fff",
                    pointHighlightFill: "#fff",
                    pointHighlightStroke: "rgba(220,220,220,1)",
                    data: [65, 59, 80, 81, 56]
                },
                {
                    label: "My Second dataset",
                    fillColor: "rgba(151,187,205,0.2)",
                    strokeColor: "rgba(151,187,205,1)",
                    pointColor: "rgba(151,187,205,1)",
                    pointStrokeColor: "#fff",
                    pointHighlightFill: "#fff",
                    pointHighlightStroke: "rgba(151,187,205,1)",
                    data: [28, 48, 40, 19, 86]
                }
            ]
        };
        var pdata = [
            {
                value: 300,
                color: "#46BFBD",
                highlight: "#5AD3D1",
                label: "Complete"
            },
            {
                value: 50,
                color:"#F7464A",
                highlight: "#FF5A5E",
                label: "In-Progress"
            }
        ];


        var ctxp = $("#pieChartDemo").get(0).getContext("2d");
        var pieChart = new Chart(ctxp).Pie(pdata);
    </script>

@stop