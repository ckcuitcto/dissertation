@extends('layouts.default')
@section('title')
    STU| Phieu Danh Gia {{ $evaluationForm->Student->User->name }}

@endsection
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
                            <table class="table table-hover table-bordered table-responsive">
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
                                                        @if( $evaluationForm->Student->User->id == $user->id )
                                                            @php $name= "proof".$valueLevel2->id; @endphp
                                                            <input type="file" class="proof" id="{{$valueLevel2->id}}"
                                                                   name="{{ $name."[]" }}" multiple>
                                                        @endif
                                                    @endisset
                                                    @if(!empty($proofs->where('evaluation_criteria_id',$valueLevel2->id)))
                                                        @foreach($proofs->where('evaluation_criteria_id',$valueLevel2->id) as $proof)
                                                            <p>
                                                                <a data-proof-id="{{$proof->id}}" id="proof-view-file"
                                                                   data-proof-file-path="{{ asset('upload/proof/'.$proof->name) }}"
                                                                   data-link-update-proof-file="{{ route('update-valid-proof-file',$proof->id ) }}"
                                                                   data-get-file-link="{{route('evaluation-form-get-file',$proof->id)}}">
                                                                    <i class="fa fa-eye"
                                                                       aria-hidden="true"></i>{{ $proof->name }}
                                                                    @if($proof->valid == 1)
                                                                        <button type="button" class="btn btn-primary btn-sm"><i class="fa fa-check {{ "proofId_".$proof->id }}" aria-hidden="true"></i></button>
                                                                    @else
                                                                        <button type="button" class="btn btn-danger btn-sm"><i class="fa fa-times {{ "proofId_".$proof->id }}" aria-hidden="true"></i></button>
                                                                    @endif

                                                                </a>
                                                            </p>
                                                        @endforeach
                                                    @endif
                                                    {!!  \App\Http\Controllers\Evaluation\EvaluationFormController::handleDetail($valueLevel2->detail)  !!}
                                                </td>
                                            @else
                                                <td>
                                                    {{ $valueLevel2->content }}
                                                    @isset($valueLevel2->proof)
                                                        @if( $evaluationForm->Student->User->id == $user->id )
                                                            @php $name= "proof".$valueLevel2->id; @endphp
                                                            <input type="file" class="proof" id="{{$valueLevel2->id}}"
                                                                   name="{{ $name."[]" }}" multiple>
                                                        @endif
                                                    @endisset
                                                    @if(!empty($proofs->where('evaluation_criteria_id',$valueLevel2->id)))
                                                        @foreach($proofs->where('evaluation_criteria_id',$valueLevel2->id) as $proof)
                                                            <p>
                                                                <a data-proof-id="{{$proof->id}}" id="proof-view-file"
                                                                   data-proof-file-path="{{ asset('upload/proof/'.$proof->name) }}"
                                                                   data-link-update-proof-file="{{ route('update-valid-proof-file',$proof->id ) }}"
                                                                   data-get-file-link="{{route('evaluation-form-get-file',$proof->id)}}">
                                                                    <i class="fa fa-eye"
                                                                       aria-hidden="true"></i>{{ $proof->name }}

                                                                @if($proof->valid == 1)
                                                                    <button type="button" class="btn btn-primary btn-sm"><i class="fa fa-check {{ "proofId_".$proof->id }}" aria-hidden="true"></i></button>
                                                                @else
                                                                    <button type="button" class="btn btn-danger btn-sm"><i class="fa fa-times {{ "proofId_".$proof->id }}" aria-hidden="true"></i></button>
                                                                @endif
                                                                </a>
                                                            </p>
                                                        @endforeach
                                                    @endif
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
                                                        {{-- nếu có tồn tại minh chứng cho tiêu chí thì sẽ hiện ra form input--}}
                                                        @isset($valueLevel3->proof)
                                                            {{-- để hiện form input thì thêm 1 điều khiện là user phải là chủ form.--}}
                                                            @if( $evaluationForm->Student->User->id == $user->id )
                                                                @php $name= "proof".$valueLevel3->id; @endphp
                                                                <input type="file" class="proof" id="{{$valueLevel3->id}}"
                                                                       name="{{ $name."[]" }}" multiple>
                                                            @endif
                                                        @endisset
                                                        @if(!empty($proofs->where('evaluation_criteria_id',$valueLevel3->id)))
                                                            @foreach($proofs->where('evaluation_criteria_id',$valueLevel3->id) as $proof)
                                                                <p>
                                                                    <a data-proof-id="{{$proof->id}}"
                                                                       id="proof-view-file"
                                                                       data-proof-file-path="{{ asset('upload/proof/'.$proof->name) }}"
                                                                       data-link-update-proof-file="{{ route('update-valid-proof-file',$proof->id ) }}"
                                                                       data-get-file-link="{{route('evaluation-form-get-file',$proof->id)}}">
                                                                        <i class="fa fa-eye"
                                                                           aria-hidden="true"></i>{{ $proof->name }}

                                                                    @if($proof->valid == 1)
                                                                        <button type="button" class="btn btn-primary btn-sm"><i class="fa fa-check {{ "proofId_".$proof->id }}" aria-hidden="true"></i></button>
                                                                    @else
                                                                        <button type="button" class="btn btn-danger btn-sm"><i class="fa fa-times {{ "proofId_".$proof->id }}" aria-hidden="true"></i></button>
                                                                    @endif
                                                                    </a>
                                                                </p>
                                                            @endforeach
                                                        @endif
                                                        {!!  \App\Http\Controllers\Evaluation\EvaluationFormController::handleDetail($valueLevel3->detail)  !!}
                                                    </td>
                                                @else
                                                    <td>
                                                        {{ $valueLevel3->content }}
                                                        @isset($valueLevel3->proof)
                                                            @if( $evaluationForm->Student->User->id == $user->id )
                                                                @php $name= "proof".$valueLevel3->id; @endphp
                                                                <input type="file" class="proof" id="{{$valueLevel3->id}} "
                                                                       name="{{ $name."[]" }}" multiple>
                                                            @endif
                                                        @endisset
                                                        @if(!empty($proofs->where('evaluation_criteria_id',$valueLevel3->id)))
                                                            @foreach($proofs->where('evaluation_criteria_id',$valueLevel3->id) as $proof)
                                                                <p>
                                                                    <a data-proof-id="{{$proof->id}}"
                                                                       id="proof-view-file"
                                                                       data-proof-file-path="{{ asset('upload/proof/'.$proof->name) }}"
                                                                       data-link-update-proof-file="{{ route('update-valid-proof-file',$proof->id ) }}"
                                                                       data-get-file-link="{{route('evaluation-form-get-file',$proof->id)}}">
                                                                        <i class="fa fa-eye"
                                                                           aria-hidden="true"></i>{{ $proof->name }}
                                                                    @if($proof->valid == 1)
                                                                        <button type="button" class="btn btn-primary btn-sm"><i class="fa fa-check {{ "proofId_".$proof->id }}" aria-hidden="true"></i></button>
                                                                    @else
                                                                        <button type="button" class="btn btn-danger btn-sm"><i class="fa fa-times {{ "proofId_".$proof->id }}" aria-hidden="true"></i></button>
                                                                    @endif
                                                                    </a>
                                                                </p>
                                                            @endforeach
                                                        @endif
                                                    </td>
                                                @endif
                                                <td>{{ $valueLevel3->mark_range_display OR "" }}</td>
                                                @foreach($listUserMark as $role)
                                                    @php
                                                        $name= "score".$valueLevel3->id;
                                                        $keyResult = $valueLevel3->id."_".$role['userId'];
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
                                                if(!empty($evaluationResults[$keyResult]['marker_score'])){
                                                    $arrTotalScore[$role['userRole']] += $evaluationResults[$keyResult]['marker_score'];
                                                }else{
                                                    $arrTotalScore[$role['userRole']] += 0;
                                                }
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

                            {{-- nếu user đang đăg nhập có quyền = quyền user đang có thể chấm thì mới hiện ra nút lưu--}}
                            @if($currentRoleCanMark->weight == $user->Role->weight)
                                @can('can-mark')
                                    <div align="right" id="button-submit-form">
                                        <button class="btn btn-primary" type="submit">Lưu</button>
                                        <button class="btn btn-secondary" type="reset">Hủy</button>
                                    </div>
                                @endcan
                            @endif
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="myModal" role="dialog">
            <div class="modal-dialog modal-lg" role="document">
                <div class="overlay">
                    <form id="proof-form" method="post">
                        {!! csrf_field() !!}
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Xem file minh chứng</h5>
                                <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span
                                            aria-hidden="true">×</span></button>
                            </div>
                            <div class="modal-body">
                                <div id="iframe-view-file"></div>
                                {{--<iframe id="frame-view-file" class="doc"></iframe>--}}
                                <input type="hidden" class="id" name="id" id="proofId">
                                {{--khi bấm vào modal. thì chỉ những ng khác k phải là chủ của phiếu mới đc chỉnh sửa file có hợp lệ hay k--}}
                                @if( $evaluationForm->Student->User->id != $user->id )
                                <div class="row">
                                    <div class="col-md-2">
                                        <fieldset class="form-group">
                                            <label for="">File hợp lệ?</label>
                                            <div class="form-check">
                                                <label class="form-check-label">
                                                    <input class="form-check-input valid" id="valid" type="radio"  name="valid" value="1">Có
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <label class="form-check-label">
                                                    <input class="form-check-input valid" id="invalid" type="radio"  name="valid" value="0">Không
                                                </label>
                                            </div>
                                        </fieldset>
                                    </div>
                                    <div class="col-md-7">
                                        <div class="form-group" id="textarea-note" style="display: none;">
                                            <label for="note">Ghi chú</label>
                                            <textarea class="form-control note" name="note" id="note"
                                                      rows="3"></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <p></p>
                                            <button class="btn btn-primary" id="btn-update-valid-proof-file"
                                                    name="btn-update-valid-proof-file">Sửa
                                            </button>
                                            <button class="btn btn-secondary" id="closeForm" type="button" data-dismiss="modal">
                                                Đóng
                                            </button>
                                        </div>

                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>
@endsection
@section('sub-javascript')
    <script type="text/javascript" src=" {{ asset('template/js/plugins/bootstrap-notify.min.js') }}"></script>
    <script type="text/javascript" src=" {{ asset('template/js/plugins/sweetalert.min.js') }}"></script>
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

            $("a#proof-view-file").click(function (e) {
                var thisproof = $(this);
                var name = thisproof.attr('data-proof-file-path');
                var id = thisproof.attr('data-proof-id');
                var url = thisproof.attr('data-get-file-link');
                var urlUpdateProofFile = thisproof.attr('data-link-update-proof-file');

                $.ajax({
                    url: url,
                    type: 'POST',
                    dataType: 'json',
                    data: {id: id},
                    success: function (data) {
                        if (data.status === true && data.proof !== undefined) {
                            $("form#proof-form").attr("data-link", urlUpdateProofFile);
                            $.each(data.proof, function (elementName, value) {
                                if (elementName === 'name') {
                                    var urlFile = '{{ asset("upload/proof/") }}' + '/' + value;
                                    var contentView = '<iframe class="doc" src="' + urlFile + '"></iframe>';
                                    $('div#iframe-view-file').html(contentView);
                                } else if (elementName === 'valid') {
                                    if (value == 1) {
                                        $('form#proof-form').find('#valid').attr('checked', true);
                                        $("form#proof-form").find('#textarea-note').hide();
                                    } else if(value == 0) {
                                        $('form#proof-form').find('#invalid').attr('checked', true);
                                        $("form#proof-form").find('#textarea-note').show();
                                    }
                                } else
                                {
                                    $('form#proof-form').find('.' + elementName).val(value);
                                }
                            });
                            $('#myModal').modal('show');
                        }
                    }
                });
            });

            $("form#proof-form").submit(function (e) {
                e.preventDefault();
                var formData = new FormData(this);
                var url = $(this).attr("data-link");
                var proofId = formData.get('id');
                // console.log(formData.get('valid'));
                $('span.messageErrors').remove();
                $.ajax({
                    type: "post",
                    url: url,
                    data: formData,
                    dataType: 'json',
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function (result) {
                        // console.log(result);
                        if (result.status === true) {
                            if(formData.get('valid') == 1){
                                $("i.proofId_"+proofId).removeClass('fa-times').addClass('fa-check');
                                $("i.proofId_"+proofId).parent().removeClass('btn-danger').addClass('btn-primary');
                            }else{
                                $("i.proofId_"+proofId).removeClass('fa-check').addClass('fa-times');
                                $("i.proofId_"+proofId).parent().removeClass('btn-primary').addClass('btn-danger');
                            }

                            $('#myModal').modal('hide');
                            $.notify({
                                title: "Cập nhật thành công : ",
                                message: ":D",
                                icon: 'fa fa-check'
                            }, {
                                type: "info"
                            });
                        } else {
                            $('form#proof-form').find('.note').parents('.form-group').append('<span class="messageErrors" style="color:red">' + result.arrMessages.note + '</span>');
                        }
                    }
                });
            });

            $("input.valid").change(function () {
                if ($(this).val() == 1) {
                    $("form#proof-form").find('#textarea-note').hide();
                } else {
                    $("form#proof-form").find('#textarea-note').show();
                }
            });

            $('#myModal').on('hidden.bs.modal', function (e) {
                $('#myModal').find('div#iframe-view-file').html('');
                $('#myModal').find("input[type=text],input[type=number], select").val('');
                $('span.messageErrors').remove();
                $('#myModal').find("#note").html('');
            });
        });
    </script>
@endsection