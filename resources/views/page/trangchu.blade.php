@extends('master')
@section('content')
      <div class="app-title">
        <div>
          <h1><i class="fa fa-dashboard"></i> Trang chủ</h1>
          <p>Trường đại học Công nghệ Sài Gòn</p>
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
                  <img class="d-block w-100 carousel-size" src="https://goo.gl/NH1z3m" alt="First slide" >
                </div>
                <div class="carousel-item">
                  <img class="d-block w-100 carousel-size" src="https://goo.gl/NH1z3m" alt="Second slide">
                </div>
                <div class="carousel-item">
                  <img class="d-block w-100 carousel-size" src="https://goo.gl/NH1z3m" alt="Third slide">
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
          <a href="page-timetable.html" style="text-decoration:none;">
              <div class="widget-small primary coloured-icon"><i class="icon fa fa-calendar-o fa-3x"></i>
                <div class="info">
                  <h4>Thời Khóa Biểu</h4>
                  <p><b>5</b></p>
                </div>
              </div>
          </a>          
        </div>
        <div class="col-md-12 col-lg-6">
          <div class="widget-small info coloured-icon"><i class="icon fa fa-thumbs-o-up fa-3x"></i>
            <div class="info">
              <h4>Điểm Học Kỳ</h4>
              <p><b>25</b></p>
            </div>
          </div>
        </div>
        <div class="col-md-12 col-lg-6">
          <div class="widget-small warning coloured-icon"><i class="icon fa fa-calendar fa-3x"></i>
            <div class="info">
              <h4>Lịch Thi Học Kỳ</h4>
              <p><b>10</b></p>
            </div>
          </div>
        </div>
        <div class="col-md-12 col-lg-6">
          <a href="page-tuition.html" style="text-decoration:none;">
              <div class="widget-small danger coloured-icon"><i class="icon fa fa-money fa-3x"></i>
                <div class="info">
                  <h4>Học Phí</h4>
                  <p><b>500</b></p>
                </div>
              </div>
          </a>          
        </div>
      </div>
      <div class="row">
        <div class="col-md-6">
          <div class="tile">
            <h3 class="tile-title">Thông báo</h3>
            <div class="alert alert-primary" role="alert">
              This is a primary alert—check it out!
            </div>
            <div class="alert alert-success" role="alert">
              This is a success alert—check it out!
            </div>
            <div class="alert alert-danger" role="alert">
              This is a danger alert—check it out!
            </div>
            <div class="alert alert-warning" role="alert">
              This is a warning alert—check it out!
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="tile">
            <h3 class="tile-title">Tin tức & sự kiện</h3>
            <div class="alert alert-primary" role="alert">
              This is a primary alert—check it out!
            </div>
            <div class="alert alert-success" role="alert">
              This is a success alert—check it out!
            </div>
            <div class="alert alert-danger" role="alert">
              This is a danger alert—check it out!
            </div>
            <div class="alert alert-warning" role="alert">
              This is a warning alert—check it out!
            </div>
          </div>
        </div>
      </div>

      <div class="row">
          <div class="col-md-6">
            <div class="tile">
              <h3 class="tile-title">Thông tin tuyển dụng</h3>
              <div class="alert alert-primary" role="alert">
                This is a primary alert—check it out!
              </div>
              <div class="alert alert-success" role="alert">
                This is a success alert—check it out!
              </div>
              <div class="alert alert-danger" role="alert">
                This is a danger alert—check it out!
              </div>
              <div class="alert alert-warning" role="alert">
                This is a warning alert—check it out!
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="tile">
              <h3 class="tile-title">Chủ đề</h3>
              <div class="alert alert-primary" role="alert">
                This is a primary alert—check it out!
              </div>
              <div class="alert alert-success" role="alert">
                This is a success alert—check it out!
              </div>
              <div class="alert alert-danger" role="alert">
                This is a danger alert—check it out!
              </div>
              <div class="alert alert-warning" role="alert">
                This is a warning alert—check it out!
              </div>
            </div>
          </div>
        </div>
@endsection