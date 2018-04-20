@extends('layouts.default')

@section('content')
    <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class="fa fa-home fa-lg" aria-hidden="true"></i> Trang chủ</h1>
                <p>Trường Đại học Công nghệ Sài Gòn</p>
            </div>
            <ul class="app-breadcrumb breadcrumb">
                <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
                <li class="breadcrumb-item"><a href="#">Trang chủ</a></li>
            </ul>
        </div>

        <div class="container">
            <div id="carouselExampleControls" class="carousel slide" data-ride="carousel">
                <div class="carousel-inner">
                  <div class="carousel-item active">
                    <img class="d-block w-100 carousel-size" src="https://goo.gl/NH1z3m" alt="First slide"  style="height:400px">
                  </div>
                  <div class="carousel-item">
                    <img class="d-block w-100 carousel-size" src="https://goo.gl/NH1z3m" alt="Second slide" style="height:400px">
                  </div>
                  <div class="carousel-item">
                    <img class="d-block w-100 carousel-size" src="https://goo.gl/NH1z3m" alt="Third slide" style="height:400px">
                  </div>
                </div>
                <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
                  <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                  <span class="sr-only">Previous</span>
                </a>
                <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
                  <span class="carousel-control-next-icon" aria-hidden="true"></span>
                  <span class="sr-only">Next</span>
                </a>
            </div>
        </div>

        <div class="row">&nbsp;</div>

        <div class="row">
            <div class="col-md-12 col-lg-6">
                <div class="widget-small primary coloured-icon"><i class="icon fa fa-calendar-o fa-3x"></i>
                    <div class="info">
                        <h4>Thời Khóa Biểu</h4>
                    </div>
                </div>
            </div>
            <div class="col-md-12 col-lg-6">
                <div class="widget-small info coloured-icon"><i class="icon fa fa-thumbs-o-up fa-3x"></i>
                    <div class="info">
                        <h4>Điểm Học Kỳ</h4>
                    </div>
                </div>
            </div>
            <div class="col-md-12 col-lg-6">
                <div class="widget-small warning coloured-icon"><i class="icon fa fa-calendar fa-3x"></i>
                    <div class="info">
                        <h4>Lịch Thi Học Kỳ</h4>
                    </div>
                </div>
            </div>
            <div class="col-md-12 col-lg-6">
                <div class="widget-small danger coloured-icon"><i class="icon fa fa-money fa-3x"></i>
                    <div class="info">
                        <h4>Học Phí</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="tile">
                    <h3 class="tile-title">Thông báo</h3>
                    <div class="alert alert-primary" role="alert">
                        STU tham gia chương trình Tư vấn Hướng nghiệp, xét tuyển ĐH - CĐ năm 2018 tại khu vực Tây Nguyên, Nam Trung Bộ và Tây Nam Bộ.
                    </div>
                    <div class="alert alert-primary" role="alert">
                        STU tham gia chương trình Tư vấn Hướng nghiệp, xét tuyển ĐH - CĐ năm 2018 tại khu vực Tây Nguyên, Nam Trung Bộ và Tây Nam Bộ.
                    </div>
                    <div align="right"><a href="#">Xem tất cả ></a></div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="tile">
                    <h3 class="tile-title">Tin tức & sự kiện</h3>
                    <div class="alert alert-success" role="alert">
                        STU tổ chức Ngày hội tư vấn Hướng nghiệp và Tuyển sinh năm 2018 
                    </div>
                    <div class="alert alert-success" role="alert">
                        STU tổ chức Ngày hội tư vấn Hướng nghiệp và Tuyển sinh năm 2018 
                    </div>
                    <div align="right"><a href="#">Xem tất cả ></a></div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="tile">
                    <h3 class="tile-title">Thông tin tuyển dụng</h3>
                    <div class="alert alert-danger" role="alert">
                        Dấu ấn STU trong ngày hội Tư vấn tuyển sinh - hướng nghiệp tại...
                    </div>
                    <div class="alert alert-danger" role="alert">
                        Dấu ấn STU trong ngày hội Tư vấn tuyển sinh - hướng nghiệp tại...
                    </div>
                    <div align="right"><a href="#">Xem tất cả ></a></div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="tile">
                    <h3 class="tile-title">Chủ đề</h3>
                    <div class="alert alert-warning" role="alert">
                        Link nhận hình trao bằng tốt nghiệp năm 2017
                      </div>
                      <div class="alert alert-info" role="alert">                        
                        Thông báo về việc đăng ký chủ trì thực hiện đề tài, dự án khoa học công nghệ tỉnh Bến Tre
                      </div>
                      <div align="right"><a href="#">Xem tất cả ></a></div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="tile">
                    <h3 class="tile-title">Pie Chart</h3>
                    <div class="embed-responsive embed-responsive-16by9">
                        <canvas class="embed-responsive-item" id="pieChartDemo"></canvas>
                    </div>
                </div>
            </div>
        </div>
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