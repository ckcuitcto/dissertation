@extends('layouts.default')

@section('content')
    <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class="fa fa-file-text-o"></i> Phiếu đánh giá điểm rèn luyện</h1>
                <p>Trường Đại học Công nghệ Sài Gòn</p>
            </div>
            <ul class="app-breadcrumb breadcrumb side">
                <li class="breadcrumb-item"><a href="{{ route('home')}}"><i class="fa fa-home fa-lg"></i></a></li>
                <li class="breadcrumb-item">Phiếu đánh giá điểm rèn luyện</li>
            </ul>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="tile">
                    <div class="tile-body">
                        <div class="tile user-settings">
                            <h4 class="line-head">Thông tin sinh viên</h4>
                            <div class="row">
                                <div class="col-md-6">
                                    <div>- Họ và tên: {{ $evaluationForm->Student->User->name }}</div>
                                    <div>- Lớp: {{$evaluationForm->Student->Classes->name OR ""}}</div>
                                    <div>- MSSV: {{ $evaluationForm->Student->user_id OR ""}}</div>
                                </div>
                                <div class="col-md-6">
                                    <div>- Khoa: {{ $evaluationForm->Student->Classes->Faculty->name OR ""}}</div>
                                    <div>- Niên
                                        khóa: {{ $evaluationForm->Student->academic_year_from . " - " . $evaluationForm->Student->academic_year_to }}</div>
                                </div>
                            </div>
                        </div>
                        <form name="evaluation-form" id="evaluation-form"
                              action="{{ route('evaluation-form-update',$evaluationForm->id) }}" method="post"
                              enctype="multipart/form-data">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            {{--<input type="hidden" name="evaluationFormId" value="{{ $evaluationForm->id }}">--}}
                            <table class="table table-hover table-bordered">
                                <tbody>
                                <tr>
                                    <td><strong>Nội dung đánh giá</strong></td>
                                    <td><strong>Thang điểm</strong></td>
                                    @foreach($listRoleCanMark as $role)
                                        <td><b> {{ $role->display_name }}</b></td>
                                    @endforeach
                                </tr>
                                <tr class="text-center">
                                    <td>(1)</td>
                                    <td>(2)</td>
                                    @foreach($listRoleCanMark as $key => $role)
                                        <td>({{ $key +3 }})</td>
                                    @endforeach
                                </tr>
                                @foreach($evaluationCriterias as $key => $valueLevel1)
                                    <tr>
                                        <td colspan="{{ 2 + count($listRoleCanMark) }}">
                                            <b> {{ $valueLevel1->content }}</b></td>
                                    </tr>
                                    @foreach($valueLevel1->Child as $valueLevel2)
                                        <tr>
                                            @if($valueLevel2->detail)
                                                <td class='detail-evaluation-form'>
                                                    {{ $valueLevel2->content }}
                                                    @isset($valueLevel2->proof)
                                                        @php $name= "proof".$valueLevel2->id; @endphp
                                                        <input type="file" class="proof" id="{{$valueLevel2->id}}"
                                                               name="{{ $name }}" multiple>
                                                    @endisset
                                                    {!!  \App\Http\Controllers\Evaluation\EvaluationFormController::handleDetail($valueLevel2->detail)  !!}
                                                </td>
                                            @else
                                                <td>
                                                    {{ $valueLevel2->content }}
                                                    @isset($valueLevel2->proof)
                                                        @php $name= "proof".$valueLevel2->id; @endphp
                                                        <input type="file" class="proof" id="{{$valueLevel2->id}}"
                                                               name="{{ $name }}" multiple>
                                                    @endisset
                                                </td>
                                            @endif
                                            <td>
                                                {{ $valueLevel2->mark_range_display OR "" }}
                                            </td>
                                            @isset($valueLevel2->mark_range_display)
                                                @foreach($listRoleCanMark as $role)
                                                    @if($role->name == $user->Role->name)
                                                        @php $name= "score".$valueLevel2->id; @endphp
                                                        <td><input required
                                                                   type="number"
                                                                   name={{$name}} value="0"
                                                                   min="{{$valueLevel2->mark_range_from}}"
                                                                   max="{{$valueLevel2->mark_range_to}}"
                                                                   id="{{ "child_".$valueLevel1->id}}"
                                                                   class="form-control {{ $role->name }}">
                                                        </td>
                                                    @else
                                                        <td><input type="number" disabled="true"
                                                                   class="form-control {{ $role->name }}"></td>
                                                    @endif
                                                @endforeach
                                            @endisset
                                        </tr>
                                        @foreach($valueLevel2->Child as $valueLevel3)
                                            <tr>
                                                @if($valueLevel3->detail)
                                                    <td class='detail-evaluation-form'>
                                                        {{ $valueLevel3->content }}
                                                        @isset($valueLevel3->proof)
                                                            @php $name= "proof".$valueLevel3->id; @endphp
                                                            <input type="file" class="proof" id="{{$valueLevel3->id}}"
                                                                   name="{{ $name }}" multiple>
                                                        @endisset
                                                        {!!  \App\Http\Controllers\Evaluation\EvaluationFormController::handleDetail($valueLevel3->detail)  !!}
                                                    </td>
                                                @else
                                                    <td>
                                                        {{ $valueLevel3->content }}
                                                        @isset($valueLevel3->proof)
                                                            @php $name= "proof".$valueLevel3->id; @endphp
                                                            <input type="file" class="proof" id="{{$valueLevel3->id}} "
                                                                   name="{{ $name }}" multiple>
                                                        @endisset
                                                    </td>
                                                @endif
                                                <td>{{ $valueLevel3->mark_range_display OR "" }}</td>
                                                @foreach($listRoleCanMark as $role)
                                                    @if($role->name == $user->Role->name)
                                                        @php $name= "score".$valueLevel3->id; @endphp
                                                        <td><input required type="number" name={{ $name }}
                                                                    value="0"
                                                                   min="{{$valueLevel3->mark_range_from}}"
                                                                   max="{{$valueLevel3->mark_range_to}}"
                                                                   id="{{ "child_".$valueLevel1->id}}"
                                                                   class="form-control  {{ $role->name }}">
                                                        </td>
                                                    @else
                                                        <td><input type="number" disabled="true"
                                                                   class="form-control {{ $role->name }}"></td>
                                                    @endif
                                                @endforeach
                                            </tr>
                                        @endforeach
                                    @endforeach
                                    <tr>
                                        <td colspan="2" class="text-center">
                                            <b>Tổng {{ \App\Helpers\Convert::numberToRomanRepresentation($key +1) }}.
                                                (Tối đa {{ $valueLevel1->mark_range_to }} điểm) </b></td>
                                        @foreach($listRoleCanMark as $role)
                                            @if($role->name == $user->Role->name)
                                                @php $name= "score".$valueLevel1->id; @endphp
                                                <td>
                                                    <input type="number"
                                                           name={{ $name }}
                                                                   required
                                                           id="{{ "total_".$valueLevel1->id}}"
                                                           value="0"
                                                           min="{{$valueLevel1->mark_range_from}}"
                                                           max="{{$valueLevel1->mark_range_to}}"
                                                           topic="totalTopic"
                                                           readonly
                                                           class="form-control {{ $role->name }}">
                                                </td>
                                            @else
                                                <td><input type="number" disabled="true"
                                                           class="form-control {{ $role->name }}"></td>
                                            @endif
                                        @endforeach
                                    </tr>

                                @endforeach

                                <tr>
                                    <td>Tổng cộng</td>
                                    <td>0 - 100</td>
                                    @foreach($listRoleCanMark as $role)
                                        @if($role->name == $user->Role->name)
                                            <td><input type="text"
                                                       class="form-control {{ $role->name }}"
                                                       required
                                                       value="0"
                                                       name="totalScoreOfForm"
                                                       id="totalScoreOfForm"
                                                       readonly
                                                >
                                            </td>
                                        @else
                                            <td><input type="number" disabled="true"
                                                       class="form-control {{ $role->name }}"></td>
                                        @endif
                                    @endforeach
                                </tr>
                                <tr>
                                    <td>Xếp loại</td>
                                    <td colspan="{{ count($listRoleCanMark) }}" style="background-color:gray"></td>
                                    <td></td>
                                </tr>
                                </tbody>
                            </table>
                            <div align="right">
                                <button class="btn btn-primary" type="submit">Lưu</button>
                                <button class="btn btn-secondary" type="reset">Hủy</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>

@endsection

@section('sub-javascript')
    <script type="text/javascript" src="{{ asset('template/js/plugins/jquery.dataTables.min.js') }} "></script>
    <script type="text/javascript" src="{{ asset('template/js/plugins/dataTables.bootstrap.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js//evaluationForm.js') }}"></script>

    <script type="text/javascript">
        $(document).ready(function () {
            {{--var roleLogin = "{{ $user->Role->name }}";--}}
            {{--$("input." + roleLogin).removeAttr('disabled');--}}

            $('.proof').change(function (e) {
                var urlCheckFile = "{{ route('evaluation-form-upload') }}";
                var formData = new FormData();
                var fileUpload = $(this);
                var countFile = fileUpload[0].files.length;
                for (var x = 0; x < countFile; x++) {
                    file = fileUpload[0].files[x];
                    formData.append("fileUpload[]", file);
                }
//                var file = this.files[0];
//                formData.append('fileUpload', file);
                fileUpload.next("span.messageErrors").remove();
                $.ajax({
                    type: "post",
                    url: urlCheckFile,
                    data: formData,
                    cache: false,
                    contentType: false,
//                    enctype: 'multipart/form-data',
                    processData: false,
                    success: function (result) {
                        if (result.status === false) {
                            //show error list fields
                            if (result.arrMessages !== undefined) {
                                $.each(result.arrMessages, function (elementName, arrMessagesEveryElement) {
                                    $.each(arrMessagesEveryElement, function (messageType, messageValue) {
                                        fileUpload.after('<span class="messageErrors" style="color:red"><br>' + messageValue + '</span>');
                                    });
                                });
                                $("button[type=submit]").attr('disabled', true);
                            }
                        }
                        // nếu k tìm thấy thẻ class messageErrors => hết lỗi ở các input file. cho submit
                        if ($("form#evaluation-form").find(".messageErrors").html() === undefined) {
                            $("button[type=submit]").removeAttr('disabled');
                        }
                    }
                });
            });
        });
    </script>
@endsection