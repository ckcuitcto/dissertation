@extends('layouts.default')
@section('title')
    STU| BẢNG ĐIỂM {{ $user->name }}
@endsection
@section('content')
    <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class="fa fa-laptop"></i> Tổng điểm cá nhân</h1>
                <p>Trường Đại học Công nghệ Sài Gòn</p>
            </div>
            <ul class="app-breadcrumb breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="fa fa-home fa-lg"></i></a></li>
                <li class="breadcrumb-item">Tổng điểm cá nhân</li>
            </ul>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="tile">
                    <h3 class="tile-title">Lưu ý</h3>
                    <div class="tile-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div><i class="fa fa-file-text-o" aria-hidden="true"></i> Điểm cá nhân, Điểm lớp và Điểm
                                    khoa là
                                    điểm đánh giá chưa tính điểm học tập
                                </div>
                                <div><i class="fa fa-file-text-o" aria-hidden="true"></i> Điểm tổng là điểm sau khi
                                    P.CTSV kiểm
                                    duyệt đã bao gồm điểm học tập
                                </div>
                                <div><i class="fa fa-file-text-o" aria-hidden="true"></i> Xếp loại và Điểm tổng nếu có
                                    giá trị
                                    là "_" thì đang đợi bổ sung điểm học tập
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div>Họ và tên: {{ $user->name }}</div>
                                <div>Lớp: {{ $user->Student->Classes->name OR "" }}</div>
                                <div>MSSV: {{ $user->users_id }}</div>
                                <div>Khoa: {{ $user->Faculty->name OR "" }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="tile">
                    <table class="table table-bordered" style="text-align:center">
                        <tbody>
                        <tr>
                            <td rowspan="2">STT</td>
                            <td rowspan="2">Học Kỳ</td>
                            <td rowspan="2">Năm Học</td>
                            <td colspan="4">Điểm</td>
                            <td rowspan="2">Xếp Loại</td>
                            {{--<td rowspan="2">Tình Trạng</td>--}}
                            <td rowspan="2">Tác Vụ</td>

                        </tr>
                        <tr>
                            @foreach($rolesCanMark as $role)
                                <td>{{ $role['display_name'] }}</td>
                            @endforeach
                            <td>Tổng</td>
                        </tr>
                        @foreach($evaluationForms as $key => $evaluationForm)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $evaluationForm->Semester->term }}</td>
                                <td>{{ $evaluationForm->Semester->year_from . " - " . $evaluationForm->Semester->year_to }}</td>
                                @foreach($arrRolesCanMarkWithScore[$evaluationForm->id] as $value)
                                    <td> {{ $value['totalRoleScore'] }}</td>
                                @endforeach
                                <td> {{ $evaluationForm->total OR 0 }}</td>
                                <td> {{ \App\Http\Controllers\Evaluation\EvaluationFormController::checkRank($evaluationForm->total) }} </td>
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
                        @endforeach
                        </tbody>
                    </table>
                </div>

            </div>
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
                                        <textarea class="form-control remarking_reason" rows="4" name="remarking_reason" placeholder="Vui lòng nhập lí do,sinh viên nêu rõ lí do phúc khảo"></textarea>
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
