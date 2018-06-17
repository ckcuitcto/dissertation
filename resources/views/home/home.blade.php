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
                        <a class="nav-item nav-link" target="_blank"
                           href="http://www.stu.edu.vn/vi/276/phong-dao-tao.html">Phòng đào tạo</a>
                        <a class="nav-item nav-link" target="_blank"
                           href="http://www.stu.edu.vn/vi/280/phong-cong-tac-sinh-vien.html">Phòng Công tác sinh
                            viên</a>
                    </div>
                </div>
            </nav>
        </div>

        <div class="row">
            <div class="col-md-6 col-lg-3">
                <a target="_blank" href="http://daotao1.stu.edu.vn/Default.aspx?page=thoikhoabieu"
                   style="text-decoration: none;">
                    <div class="widget-small primary coloured-icon"><i class="icon fa fa-calendar-o fa-3x"></i>
                        <div class="info">
                            <h4>Thời Khóa Biểu</h4>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-6 col-lg-3">
                <a target="_blank" href="http://daotao1.stu.edu.vn/Default.aspx?page=xemdiemthi"
                   style="text-decoration: none;">
                    <div class="widget-small info coloured-icon"><i class="icon fa fa-graduation-cap fa-3x"></i>
                        <div class="info">
                            <h4>Điểm Học Kỳ</h4>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-6 col-lg-3">
                <a target="_blank" href="http://daotao1.stu.edu.vn/Default.aspx?page=dkmonhoc"
                   style="text-decoration: none;">
                    <div class="widget-small warning coloured-icon"><i class="icon fa fa-calendar fa-3x"></i>
                        <div class="info">
                            <h4>Đăng ký môn học</h4>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-6 col-lg-3">
                <a target="_blank" href="http://daotao1.stu.edu.vn/Default.aspx?page=xemhocphi"
                   style="text-decoration: none;">
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
                        <h3 class="tile-title"><i class="fa fa-warning" aria-hidden="true"></i> Điểm rèn luyện học kì
                            hiện tại</h3>
                        <table class="table table-bordered" style="text-align:center">
                            <tbody>
                            <tr>
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
                                <td>{{ $evaluationForm->Semester->term }}</td>
                                <td>{{ $evaluationForm->Semester->year_from . " - " . $evaluationForm->Semester->year_to }}</td>
                                @foreach($arrRolesCanMarkWithScore as $role)
                                    <td> {{ $role['totalRoleScore'] }}</td>
                                @endforeach
                                <td> {{ $evaluationForm->total OR 0 }}</td>
                                <td> {{ \App\Http\Controllers\Evaluation\EvaluationFormController::checkRank($evaluationForm->total) }} </td>
                                <td> {{ \App\Http\Controllers\Controller::getDisplayStatusEvaluationForm($evaluationForm->status) }}</td>
                                <td>
                                    <a title="Xem" class="btn btn-primary"
                                       href="{{ route('evaluation-form-show',$evaluationForm->id) }}">
                                        <i class="fa fa-edit" aria-hidden="true" style="color:white"></i>
                                    </a>
                                    {{-- ếu đang trong thời gian phúc khảo và user login = user chủ fomr thì hiện nút phúc khảo --}}
                                    @if( \App\Http\Controllers\Controller::checkInTime($evaluationForm->Semester->date_start_to_request_re_mark, $evaluationForm->Semester->date_end_to_request_re_mark ))
                                        @if(empty($evaluationForm->Remaking))
                                            <button data-toggle="modal" id="btn-request-remaking" data-target="#myModal"
                                                    class="btn btn-primary"
                                                    data-id-evaluation-form="{{ $evaluationForm->id }}"
                                                    title="Yêu cầu phúc khảo">
                                                <i class="fa fa-send" aria-hidden="true" style="color:white"></i>
                                            </button>
                                        @else
                                            <button class="btn btn-primary" title="Đã gửi yêu cầu phúc khảo" disabled>
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
                                    <p style="color:#009688;">{{$news->title}}<span
                                                style="white-space: nowrap;color:grey;font-size:12px"> ( {{$news->created_at}}
                                            ) </span></p>
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
                        <h3 class="tile-title"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> &nbsp;Thời gian
                            đánh giá điểm rèn luyện</h3>
                        <div class="card mb-3">
                            <div class="card-header">
                                <div>Đánh giá điểm rèn luyện học kỳ {{$timeList->term}} năm
                                    học {{$timeList->year_to}}</div>
                            </div>
                            <div class="card-body">
                                {{-- <h5 class="card-title">Warning card title</h5> --}}
                                <p class="card-text"> {{ date('d/m/Y'),strtotime($timeList->date_start_to_mark )}}
                                    -> {{date('d/m/Y'),strtotime($timeList->date_end_to_mark )}}
                                   
                                </p>
                                <p class="card-text"> {{ date('d/m/Y'),strtotime($timeList->date_start_to_request_re_mark )}}
                                    -> {{ date('d/m/Y'),strtotime($timeList->date_end_to_request_re_mark )}}
                                    Thời gian khiếu nại
                                </p>
                                <p class="card-text">{{ date('d/m/Y'),strtotime($timeList->date_start_to_re_mark )}}
                                    -> {{ date('d/m/Y'),strtotime($timeList->date_end_to_re_mark )}}
                                    Thời gian chấm lại khiếu nại
                                </p>

                            </div>
                        </div>
                    </div>
                </div>
            @endisset
        </div>

        <div class="modal fade" id="myModal" role="dialog">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Yêu cầu phúc khảo</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span
                                    aria-hidden="true">×</span></button>
                    </div>
                    <div class="modal-body">
                        <form id="remarking-form">
                            <input type="hidden" id="evluationFormId" name="formId">
                            {!! csrf_field() !!}
                            <div class="col-md-12">
                                <h3 class="tile-title">Lý do phúc khảo</h3>
                                <div class="tile-body">
                                    <div class="form-group">
                                        <textarea class="form-control remarking_reason" rows="4" name="remarking_reason"
                                                  placeholder="Vui lòng nhập lí do,sinh viên nêu rõ lí do phúc khảo"></textarea>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <div class="modal-footer">
                            <button data-link="{{ route('remaking-store') }}" class="btn btn-primary"
                                    id="btn-send-remaking" name="btn-send-remaking" type="button">
                                <i class="fa fa-fw fa-lg fa-check-circle"></i>Gửi
                            </button>
                            <button class="btn btn-secondary" id="closeForm" type="button" data-dismiss="modal">
                                <i class="fa fa-fw fa-lg fa-times-circle"></i>Đóng
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
@section('sub-javascript')
    <script>
        $(document).ready(function () {

            $("button#btn-request-remaking").click(function () {
                var evaluationFormId = $(this).attr('data-id-evaluation-form');
                $("form#remarking-form").find("input#evluationFormId").val(evaluationFormId);
            });

            $("button#btn-send-remaking").click(function () {
                var valueForm = $('form#remarking-form').serialize();
                var url = $(this).attr('data-link');
                $('.form-group').find('span.messageErrors').remove();
                $.ajax({
                    type: "post",
                    url: url,
                    data: valueForm,
                    dataType: 'json',
                    success: function (result) {
                        if (result.status === false) {
                            //show error list fields
                            if (result.arrMessages !== undefined) {
                                $.each(result.arrMessages, function (elementName, arrMessagesEveryElement) {
                                    $.each(arrMessagesEveryElement, function (messageType, messageValue) {
                                        $('form#remarking-form').find('.' + elementName).parents('.form-group').append('<span class="messageErrors" style="color:red">' + messageValue + '</span>');
                                    });
                                });
                            }
                        } else if (result.status === true) {
                            $('div#myModal').find('.modal-body').html('<p>Gửi yêu cầu phúc khảo thành công</p>');
                            $("div#myModal").find('.modal-footer').html('<button  class="btn btn-default" data-dismiss="modal">Đóng</button>');
                            $('div#myModal').on('hidden.bs.modal', function (e) {
                                location.reload();
                            });
                        }
                    }
                });
            });
        })
    </script>
@endsection