@extends('layouts.default')
@section('content')
    <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class="fa fa-home fa-lg" aria-hidden="true"></i> Trang chủ</h1>
                <p>Trường Đại học Công nghệ Sài Gòn</p>
            </div>
            {{-- <ul class="app-breadcrumb breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home')}}"><i class="fa fa-home fa-lg"></i></a></li>
                <li class="breadcrumb-item">Trang chủ</li>
            </ul> --}}

            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                {{-- <a class="navbar-brand" href="#">Navbar</a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                  <span class="navbar-toggler-icon"></span>
                </button> --}}
                <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                  <div class="navbar-nav">
                    <a class="nav-item nav-link" target="_blank" href="http://www.stu.edu.vn/vi/276/phong-dao-tao.html">Phòng đào tạo</a>
                    <a class="nav-item nav-link" target="_blank" href="http://www.stu.edu.vn/vi/280/phong-cong-tac-sinh-vien.html">Phòng Công tác sinh viên</a>
                    <a class="nav-item nav-link" target="_blank" href="http://www.stu.edu.vn/vi/265/khoa-cong-nghe-thong-tin.html">Công nghệ thông tin</a>
                    <a class="nav-item nav-link" target="_blank" href="http://www.stu.edu.vn/vi/266/khoa-ky-thuat-cong-trinh.html">Kỹ thuật công trình</a>
                    <a class="nav-item nav-link" target="_blank" href="http://www.stu.edu.vn/vi/284/khoa-cong-nghe-thuc-pham.html">Công nghệ thực phẩm</a>
                    <a class="nav-item nav-link" target="_blank" href="http://www.stu.edu.vn/vi/293/khoa-dien-dien-tu.html">Điện, điện tử</a>
                    <a class="nav-item nav-link" target="_blank" href="http://www.stu.edu.vn/vi/294/khoa-co-khi.html">Cơ khí</a>
                    <a class="nav-item nav-link" target="_blank" href="http://www.stu.edu.vn/vi/295/khoa-quan-tri-kinh-doanh.html">Quản trị kinh doanh</a>
                    <a class="nav-item nav-link" target="_blank" href="http://www.stu.edu.vn/vi/296/khoa-design.html">Design</a>
                  </div>
                </div>
            </nav>
        </div>

        <div class="row">
            <div class="col-md-6 col-lg-3">
            <a  target="_blank" href="http://daotao1.stu.edu.vn/Default.aspx?page=thoikhoabieu" style="text-decoration: none;">
                    <div class="widget-small primary coloured-icon"><i class="icon fa fa-calendar-o fa-3x"></i>
                        <div class="info">
                            <h4>Thời Khóa Biểu</h4>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-6 col-lg-3">
                <a target="_blank" href="http://daotao1.stu.edu.vn/Default.aspx?page=xemdiemthi" style="text-decoration: none;">
                    <div class="widget-small info coloured-icon"><i class="icon fa fa-graduation-cap fa-3x"></i>
                        <div class="info">
                            <h4>Điểm Học Kỳ</h4>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-6 col-lg-3">
                <a target="_blank" href="http://daotao1.stu.edu.vn/Default.aspx?page=dkmonhoc" style="text-decoration: none;">
                    <div class="widget-small warning coloured-icon"><i class="icon fa fa-calendar fa-3x"></i>
                        <div class="info">
                            <h4>Đăng ký môn học</h4>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-6 col-lg-3">
                <a target="_blank" href="http://daotao1.stu.edu.vn/Default.aspx?page=xemhocphi" style="text-decoration: none;">
                    <div class="widget-small danger coloured-icon"><i class="icon fa fa-money fa-3x"></i>
                        <div class="info">
                            <h4>Học Phí</h4>
                        </div>
                    </div>
                </a>
            </div>
        </div>
        @if(!empty($userLogin->Student))
        <div class="row">
            <div class="col-md-12">
                <div class="tile">
                    <h3 class="tile-title"><i class="fa fa-warning" aria-hidden="true"></i> Điểm rèn luyện học kì hiện tại</h3>
                    <table class="table table-bordered" style="text-align:center">
                        <tbody>
                        <tr>
                            <td rowspan="2">STT</td>
                            <td rowspan="2">Học Kỳ</td>
                            <td rowspan="2">Năm Học</td>
                            <td colspan="4">Điểm</td>
                            <td rowspan="2">Xếp Loại</td>
                            <td rowspan="2">Tình Trạng</td>
                            <td rowspan="2">Tác Vụ</td>

                        </tr>
                        <tr>
                            @foreach($arrRolesCanMarkWithScore as $role)
                                <td>{{ $role['display_name'] }}</td>
                            @endforeach
                            <td>Tổng</td>
                        </tr>

                        <tr>
                            <td>{{ 1 }}</td>
                            <td>{{ $evaluationForm->Semester->term }}</td>
                            <td>{{ $evaluationForm->Semester->year_from . " - " . $evaluationForm->Semester->year_to }}</td>
                            @foreach($arrRolesCanMarkWithScore as $role)
                                <td> {{ $role['totalRoleScore'] }}</td>
                            @endforeach
                            <td> {{ $evaluationForm->total OR 0 }}</td>
                            <td> {{ \App\Http\Controllers\Evaluation\EvaluationFormController::checkRank($evaluationForm->total) }} </td>
                            <td> {{ \App\Http\Controllers\Controller::getDisplayStatusEvaluationForm($evaluationForm->status) }}</td>
                            <td>
                                <a class="btn btn-primary"
                                   href="{{ route('evaluation-form-show',$evaluationForm->id) }}">
                                    <i class="fa fa-edit" aria-hidden="true" style="color:white"></i>Xem
                                </a>
                                {{-- ếu đang trong thời gian phúc khảo và user login = user chủ fomr thì hiện nút phúc khảo --}}
                                @if( \App\Http\Controllers\Controller::checkInTime($evaluationForm->Semester->date_start_to_request_re_mark, $evaluationForm->Semester->date_end_to_request_re_mark ) AND $user->users_id == $userLogin->users_id)
                                    @if(empty($evaluationForm->Remaking))
                                        <button data-toggle="modal" id="btn-request-remaking" data-target="#myModal" class="btn btn-primary" data-id-evaluation-form="{{ $evaluationForm->id }}"
                                                title="Yêu cầu phúc khảo">
                                            <i class="fa fa-send" aria-hidden="true" style="color:white"></i>
                                        </button>
                                    @else
                                        <button  class="btn btn-primary" title="Đã gửi yêu cầu phúc khảo" disabled>
                                            <i class="fa fa-send" aria-hidden="true" style="color:white"></i>
                                        </button>
                                    @endif

                                @endif
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif
        <div class="row">
            @if(!empty($newsList))
            <div class="col-md-6">
                <div class="tile">
                    <h3 class="tile-title"><i class="fa fa-bell" aria-hidden="true"></i> &nbsp;Tin tức</h3>
                    @foreach($newsList as $news)
                    <div class="card mb-2">
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
                                Thời gian đánh giá điểm rèn luyện. <a href="">>> Đánh giá ngay</a>
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