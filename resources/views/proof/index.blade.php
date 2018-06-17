@extends('layouts.default')

@section('content')

    <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class="fa fa-calendar-o"></i> Quản lý minh chứng</h1>
                <p>Trường Đại học Công nghệ Sài Gòn</p>
            </div>
            <ul class="app-breadcrumb breadcrumb">
                <li class="breadcrumb-item"><a href="{{route('home')}}"><i class="fa fa-home fa-lg"></i></a></li>
                <li class="breadcrumb-item"> Quản lý minh chứng</li>
            </ul>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="tile">
                    <section class="invoice">
                        <div class="tile-body">
                            <table class="table table-hover table-bordered" id="sampleTable">
                                <thead>
                                <tr>
                                    <th>STT</th>
                                    <th>Tiêu chí</th>
                                    <th>File</th>
                                    <th>Học kỳ</th>
                                    <th>Năm học</th>
                                    <th>Trạng thái</th>
                                    <th>Tác vụ</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($proofList as $key => $proof)
                                    <tr>
                                        <td>{{$key+1}}</td>
                                        <td>
                                            @isset($proof->EvaluationCriteria)
                                                {{$proof->EvaluationCriteria->content}}
                                            @endisset
                                        </td>
                                        <td>
                                            <a style="color:white" data-proof-id="{{$proof->id}}"
                                               id="proof-view-file"
                                               data-proof-file-path="{{ asset('upload/proof/'.$proof->name) }}"
                                               data-link-update-proof-file="{{ route('proof-destroy',$proof->id ) }}"
                                               data-get-file-link="{{route('evaluation-form-get-file',$proof->id)}}"
                                               class="btn btn-primary">
                                                <i class="fa fa-eye"
                                                   aria-hidden="true"></i>{{ str_limit($proof->name,20) }}
                                            </a>
                                        </td>
                                        <td>
                                            @isset($proof->Semester)
                                                {{$proof->Semester->term OR ""}}
                                            @endisset
                                        </td>
                                        <td>
                                            @isset($proof->Semester)
                                                {{$proof->Semester->year_from. "-".$proof->Semester->year_to}}
                                            @endisset
                                        </td>
                                        <td>{{ ($proof->valid) ? "Hợp lệ" : "Không hợp lệ" }}</td>
                                        <td>
                                            {{--nếu có học kì. thì phải thuộc thời gian chấm--}}
                                            @isset($proof->Semester)
                                                @if(\App\Http\Controllers\Proof\ProofController::checkTimeMark($proof->Semester->mark_time_start,$proof->Semester->mark_time_end))
                                                    <button type="button" class="btn btn-danger"
                                                            data-proof-id="{{$proof->id}}" id="proof-destroy"
                                                            data-proof-link="{{route('proof-destroy',$proof->id)}}">
                                                        <i class="fa fa-lg fa-trash"></i> Xóa
                                                    </button>
                                                @endif
                                            @endisset
                                            {{-- nếu k có học kì thì cho xóa--}}
                                            @empty($proof->Semester)
                                                <button type="button" class="btn btn-danger"
                                                        data-proof-id="{{$proof->id}}" id="proof-destroy"
                                                        data-proof-link="{{route('proof-destroy',$proof->id)}}">
                                                    <i class="fa fa-lg fa-trash"></i> Xóa
                                                </button>
                                            @endempty
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <button class="btn btn-outline-success" id="createFile" data-toggle="modal"
                                        data-target="#addModal">
                                    <i class="fa fa-plus" aria-hidden="true"></i>Thêm minh chứng
                                </button>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
        <div class="modal fade" id="myModal" role="dialog">
            <div class="modal-dialog modal-lg" role="document">
                <div class="overlay">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Xem file minh chứng</h5>
                            <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">×</span></button>
                        </div>
                        <div class="modal-body">
                            <div id="iframe-view-file" class="doc"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="addModal" role="dialog">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Chọn file minh chứng muốn thêm</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span
                                    aria-hidden="true">×</span></button>
                    </div>
                    <div class="modal-body">
                        <form id="upload-proof-form">
                            {!! csrf_field() !!}
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-row">
                                        <label for="evaluation_criteria">Chọn tiêu chí </label>
                                        <select class="form-control evaluation_criteria" name="evaluation_criteria"
                                                id="evaluationCriteria">
                                            <option value="0"> ---Chọn tiêu chí---</option>
                                            @foreach($evaluationCriterias as $value)
                                                <option value="{{$value->id}}">{{$value->content}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-row">
                                        <label for="semester">Chọn học kì</label>
                                        <select class="form-control semester" name="semester" id="semester">
                                            <option value="0"> ---Chọn học kì---</option>
                                            @foreach($semesters as $value)
                                                <option value="{{$value->id}}">{{ "Học kì $value->term. Năm học $value->year_from - $value->year_to" }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-row">
                                        <label for="fileUpload">Chọn file excel</label>
                                        <input type="file" multiple class="form-control fileUpload" name="fileUpload"
                                               id="fileUpload">
                                    </div>
                                </div>
                            </div>
                        </form>
                        <br>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="alert alert-danger show-error" style="display: none">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button data-link="{{ route('proof-store') }}" class="btn btn-primary"
                                id="btn-upload-proof" name="btn-upload-proof" type="button">
                            Thêm
                        </button>
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Đóng</button>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

@section('sub-javascript')
    <script type="text/javascript" src="{{ asset('template/js/plugins/jquery.dataTables.min.js') }} "></script>
    <script type="text/javascript" src="{{ asset('template/js/plugins/dataTables.bootstrap.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('template/js/plugins/bootstrap-notify.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('template/js/plugins/sweetalert.min.js') }}"></script>
    {{--<script type="text/javascript">$('#sampleTable').DataTable();</script>--}}


    <script>
        $(document).ready(function () {

            $("a#proof-view-file").click(function (e) {
                var thisproof = $(this);
                var name = thisproof.attr('data-proof-file-path');
                var id = thisproof.attr('data-proof-id');
                var url = thisproof.attr('data-get-file-link');
                var urlDeleteProofFile = thisproof.attr('data-link-update-proof-file');

                $.ajax({
                    url: url,
                    type: 'POST',
                    dataType: 'json',
                    data: {id: id},
                    success: function (data) {
                        if (data.status === true && data.proof !== undefined) {
                            $("form#proof-form").attr("data-link", urlDeleteProofFile);
                            $.each(data.proof, function (elementName, value) {
                                if (elementName === 'name') {
                                    var urlFile = '{{ asset("upload/proof/") }}' + '/' + value;
                                    var contentView = '<iframe class="doc" src="' + urlFile + '"></iframe>';
                                    $('div#iframe-view-file').html(contentView);
                                }
                            });
                            $('#myModal').modal('show');
                        }
                    }
                });
            });

            $('button#proof-destroy').click(function () {
                var id = $(this).attr("data-proof-id");
                var url = $(this).attr('data-proof-link');
                swal({
                    title: "Bạn chắc chưa?",
                    text: "Bạn sẽ không thể khôi phục lại dữ liệu !!",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Có, tôi chắc chắn!",
                    cancelButtonText: "Không, Hủy dùm tôi!",
                    closeOnConfirm: false,
                    closeOnCancel: false
                }, function (isConfirm) {
                    if (isConfirm) {
                        $.ajax({
                            url: url,
                            type: 'GET',
                            cache: false,
                            data: {"id": id},
                            success: function (data) {
                                if (data.status === true) {
                                    swal("Deleted!", "Đã xóa file " + data.proof.name, "success");
                                    $('.sa-confirm-button-container').click(function () {
                                        location.reload();
                                    })
                                } else {
                                    swal("Cancelled", "Không tìm thấy file !!! :)", "error");
                                }
                            }
                        });
                    } else {
                        swal("Đã hủy", "Đã hủy xóa File:)", "error");
                    }
                });
            });

            $('input#fileUpload').change(function (e) {
                e.preventDefault();
                var urlCheckFile = "{{ route('evaluation-form-upload') }}";
                var formData = new FormData();
                var fileUpload = $(this);
                var countFile = fileUpload[0].files.length;
                for (var x = 0; x < countFile; x++) {
                    file = fileUpload[0].files[x];
                    formData.append("fileUpload[]", file);
                }
                $('.form-row').find('span.messageErrors').remove();
                $.ajax({
                    type: "post",
                    url: urlCheckFile,
                    data: formData,
                    cache: false,
                    contentType: false,
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
                            }
                        }
                    }
                });
            });

            $('body').on('click', 'button#btn-upload-proof', function (e) {
                e.preventDefault();
                var formData = new FormData($('form#upload-proof-form')[0]);
                // var formData = new FormData();
                var fileUpload = document.getElementById('fileUpload');
                var inss = fileUpload.files.length;
                for (var x = 0; x < inss; x++) {
                    file = fileUpload.files[x];
                    formData.append("fileUpload[]", file);
                }
                // formData.append("semester",$("form#upload-proof-form").find("#semester").val());
                // formData.append("evaluation_criteria",$("form#upload-proof-form").find("#evaluation_criteria").val());
                var url = $(this).attr('data-link');
                $('.form-row').find('span.messageErrors').remove();
                $.ajax({
                    type: "post",
                    url: url,
                    data: formData,
                    processData: false,
                    dataType: 'json',
                    contentType: false,
                    enctype: 'multipart/form-data',
                    success: function (result) {
                        if (result.status === false) {
                            //show error list fields
                            if (result.arrMessages !== undefined) {
                                $.each(result.arrMessages, function (elementName, arrMessagesEveryElement) {
                                    $.each(arrMessagesEveryElement, function (messageType, messageValue) {
                                        $('form#upload-proof-form').find('.' + elementName).parents('.form-row').append('<span class="messageErrors" style="color:red">' + messageValue + '</span>');
                                    });
                                });
                            }
                        } else if (result.status === true) {
                            $('#addModal').find('.modal-body').html('<p>Thêm minh chứng thành công</p>');
                            $("#addModal").find('.modal-footer').html('<button  class="btn btn-default" data-dismiss="modal">Đóng</button>');
                            $('#addModal').on('hidden.bs.modal', function (e) {
                                location.reload(true);
                            });
                        }
                    }
                });
            });
        });
    </script>
@endsection