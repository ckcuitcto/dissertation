@extends('layouts.default')

@section('content')
    @php
        //  lưu lại tổng điểm từng role chấm
        foreach($listUserMark as $role)
        {
            $arrTotalScore[$role['userRole']] = 0;
        }
    @endphp
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
                                    @foreach($listUserMark as $role)
                                        <td><b> {{ $role['display_name'] }}</b></td>
                                    @endforeach
                                </tr>
                                <tr class="text-center">
                                    <td>(1)</td>
                                    <td>(2)</td>
                                    @foreach($listUserMark as $key => $role)
                                        <td>({{ $key +3 }})</td>
                                    @endforeach
                                </tr>
                                @foreach($evaluationCriterias as $key => $valueLevel1)
                                    <tr>
                                        <td colspan="{{ 2 + count($listUserMark) }}">
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
                                                @foreach($listUserMark as $role)
                                                    @php
                                                        $name= "score".$valueLevel2->id;
                                                        $keyResult = $valueLevel2->id."_".$role['userId'];

                                                        //lưu lại điểm mỗi loại role
                                                        if(!empty($evaluationResults[$keyResult]['marker_score'])){
                                                            $arrTotalScore[$role['userRole']] += $evaluationResults[$keyResult]['marker_score'];
                                                        }else{
                                                            $arrTotalScore[$role['userRole']] += 0;
                                                        }
                                                    @endphp
                                                    {{-- nếu role của user đang đăng nhập = với role của input và đang trong thời gian có thể chấm thì mới đc nhập--}}
                                                    {{-- các input còn lại sẽ bị ẩn đi --}}
                                                    @if($role['name'] == $user->Role->name AND $currentRoleCanMark->id == $role['userRole'])
                                                        <td><input required type="number" name="{{$name}}"
                                                                   value="{{ $evaluationResults[$keyResult]['marker_score'] OR 0 }}"
                                                                   min="{{$valueLevel2->mark_range_from}}"
                                                                   max="{{$valueLevel2->mark_range_to}}"
                                                                   id="{{ "child_".$valueLevel1->id}}"
                                                                   class="form-control {{ $role['name'] }}">
                                                        </td>
                                                    @else
                                                        <td>
                                                            <input type="number" disabled="true" class="form-control"
                                                                   {{ $role['name'] }} value="{{ $evaluationResults[$keyResult]['marker_score'] OR 0 }}">
                                                        </td>
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
                                                @foreach($listUserMark as $role)
                                                    @php
                                                        $name= "score".$valueLevel3->id;
                                                        $keyResult = $valueLevel3->id."_".$role['userId'];
                                                        if(!empty($evaluationResults[$keyResult]['marker_score'])){
                                                            $arrTotalScore[$role['userRole']] += $evaluationResults[$keyResult]['marker_score'];
                                                        }else{
                                                            $arrTotalScore[$role['userRole']] += 0;
                                                        }
                                                    @endphp
                                                    @if($role['name'] == $user->Role->name AND $currentRoleCanMark->id == $role['userRole'])
                                                        <td><input required type="number" name="{{ $name }}"
                                                                   value="{{ $evaluationResults[$keyResult]['marker_score'] OR 0 }}"
                                                                   min="{{$valueLevel3->mark_range_from}}"
                                                                   max="{{$valueLevel3->mark_range_to}}"
                                                                   id="{{ "child_".$valueLevel1->id}}"
                                                                   class="form-control  {{ $role['name'] }}">
                                                        </td>
                                                    @else
                                                        <td>
                                                            <input type="number" disabled="true"
                                                                   class="form-control {{ $role['name'] }}"
                                                                   value="{{ $evaluationResults[$keyResult]['marker_score'] OR 0 }}">
                                                        </td>
                                                    @endif
                                                @endforeach
                                            </tr>
                                        @endforeach
                                    @endforeach
                                    <tr>
                                        <td colspan="2" class="text-center">
                                            <b>Tổng {{ \App\Helpers\Convert::numberToRomanRepresentation($key +1) }}.
                                                (Tối đa {{ $valueLevel1->mark_range_to }} điểm) </b></td>
                                        @foreach($listUserMark as $role)
                                            @php
                                                $name= "score".$valueLevel1->id;
                                                $keyResult = $valueLevel1->id."_".$role['userId'];

                                            @endphp
                                            @if($role['name'] == $user->Role->name  AND $currentRoleCanMark->id == $role['userRole'])
                                                <td>
                                                    <input type="number"
                                                           name="{{ $name }}" required
                                                           id="{{ "total_".$valueLevel1->id}}"
                                                           value="{{ $evaluationResults[$keyResult]['marker_score'] OR 0 }}"
                                                           min="{{$valueLevel1->mark_range_from}}"
                                                           max="{{$valueLevel1->mark_range_to}}"
                                                           topic="totalTopic"
                                                           readonly
                                                           class="form-control {{ $role['name'] }}">
                                                </td>
                                            @else
                                                <td>
                                                    <input type="number" disabled="true"
                                                           class="form-control {{ $role['name'] }}"
                                                           value="{{ $evaluationResults[$keyResult]['marker_score'] OR 0 }}">
                                                </td>
                                            @endif
                                        @endforeach
                                    </tr>

                                @endforeach

                                <tr>
                                    <td>Tổng cộng</td>
                                    <td>0 - 100</td>
                                    @foreach($listUserMark as $role)
                                        @php
                                            $name= "score".$valueLevel1->id;
                                            $keyResult = $valueLevel1->id."_".$role['userId'];
                                        @endphp
                                        @if($role['name'] == $user->Role->name  AND $currentRoleCanMark->id == $role['userRole'])
                                            <td><input type="text"
                                                       class="form-control {{ $role['name'] }}"
                                                       required
                                                       value="{{ $arrTotalScore[$role['userRole']] }}"
                                                       name="totalScoreOfForm"
                                                       id="totalScoreOfForm"
                                                       readonly
                                                >
                                            </td>
                                        @else
                                            <td><input type="number" disabled="true"
                                                       value="{{ $arrTotalScore[$role['userRole']] }}"
                                                       class="form-control {{ $role['name'] }}"></td>
                                        @endif
                                    @endforeach
                                </tr>
                                <tr>
                                    <td>Xếp loại</td>
                                    <td colspan="{{ count($listUserMark) }}" style="background-color:gray"></td>
                                    <td></td>
                                </tr>
                                </tbody>
                            </table>
                            @can('can-mark')
                            <div align="right">
                                <button class="btn btn-primary" type="submit">Lưu</button>
                                <button class="btn btn-secondary" type="reset">Hủy</button>
                            </div>
                            @endcan
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>

@endsection

@section('sub-javascript')

    <script type="text/javascript" src="{{ asset('js//evaluationForm.js') }}"></script>

    <script type="text/javascript">
        $(document).ready(function () {

            //nếu quá hạn thì k thể chấm điểm
            @if( strtotime($evaluationForm->Semester->date_start_to_mark) > strtotime(date('Y-m-d')) OR strtotime($evaluationForm->Semester->date_end_to_mark) < strtotime(date('Y-m-d')))
                $('input').attr('disabled', true);
                $('button').attr('disabled', true);
            @endif

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
                        // nếu k tìm thấy thẻ có class messageErrors => hết lỗi ở các input file. cho submit
                        if ($("form#evaluation-form").find(".messageErrors").html() === undefined) {
                            $("button[type=submit]").removeAttr('disabled');
                        }
                    }
                });
            });
        });
    </script>
@endsection