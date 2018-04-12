<?php
/**
 * Created by PhpStorm.
 * User: Thai Duc
 * Date: 11-Apr-18
 * Time: 12:30 AM
 */
?>
@extends('layouts.default')
@section('content')
    <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class="fa fa-file-text-o"></i> Danh sách các lớp thuộc khoa {{ $faculty->name }}</h1>
                <p>Trường Đại học Công nghệ Sài Gòn</p>
            </div>
            <ul class="app-breadcrumb breadcrumb side">
                <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
                <li class="breadcrumb-item">Trang chủ</li>
                <li class="breadcrumb-item active"><a href="#"> Khoa {{ $faculty->name }} </a></li>
            </ul>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="tile">
                    <div class="tile faculty-setting">
                        <div id="faculty-info">
                            <h4 class="line-head">Thông tin khoa {{ $faculty->name }}</h4>
                            <div class="row">
                                <div class="col-md-10">
                                    <div class="fo"></div>
                                    <div>Số lớp : {{ count($faculty->Classes) }}</div>
                                </div>
                                <div class="col-md-2">
                                    <button class="btn btn-primary" id="btnEditFaculty" type="button"><i class="fa fa-pencil-square-o" aria-hidden="true"></i>Sửa</button>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    @include('alert.success')
                                </div>
                            </div>
                        </div>
                        <div id="faculty-edit" @if (!$errors->any()) style="display: none" @endif>
                            <form action="{{ route('faculty-edit',$faculty->id ) }}" method="post" id="form-faculty-edit">
                                {!! csrf_field() !!}
                                <div class="form-group">
                                    <label class="control-label">Tên khoa</label>
                                    <input class="form-control" name="name" value="{{ old('name') }}" id="name" required type="text" placeholder="Nhập tên mới của Khoa">
                                    @if ($errors->has('name'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                    @endif
                                </div>
                                <button class="btn btn-primary" type="submit"><i class="fa fa-fw fa-lg fa-check-circle"></i>Sửa</button>
                                &nbsp;&nbsp;&nbsp;
                                <a class="btn btn-secondary" id="btnCancelEdit" href="#"><i class="fa fa-fw fa-lg fa-times-circle"></i>Hủy</a>
                            </form>
                        </div>
                    </div>
                    <div class="tile-body">
                        <table class="table table-hover table-bordered" id="sampleTable">
                            <thead>
                            <tr>
                                <th>Lớp</th>
                                <th>Số lượng sinh viên</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($faculty->classes as $class)
                                <tr>
                                    <td><a href="{{ route('class-detail',$class->id) }}">{{ $class->name }} </a> </td>
                                    <td>{{ count($class->Students) }}</td>
                                    <td>
                                        @if(!count($class->Students)>0)
                                            <a data-class-id="{{$class->id}}" id="destroy-class" data-class-link="{{route('class-destroy',$class->id)}}">
                                                <i class="fa fa-trash-o" aria-hidden="true"> </i>
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>


    </main>

@endsection

@section('sub-javascript')
    <script type="text/javascript" src="{{ asset('template/js/plugins/jquery.dataTables.min.js') }} "></script>
    <script type="text/javascript" src="{{ asset('template/js/plugins/dataTables.bootstrap.min.js') }}"></script>
    <script type="text/javascript">$('#sampleTable').DataTable();</script>

    <script type="text/javascript" src="{{ asset('template/js/plugins/bootstrap-notify.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('template/js/plugins/sweetalert.min.js') }}"></script>

    <script type="text/javascript">
        $(document).ready(function(){
            $('div.alert-success').delay(2000).slideUp();

            $('#btnEditFaculty').click(function(){
                $("#faculty-edit").fadeToggle();
            });

            $('#btnCancelEdit').click(function(){
                $("#faculty-edit").fadeOut("slow");
            });

            $("#btn-save-class").click(function () {
//                $('#myModal').find(".modal-title").text('Thêm mới Khoa');
//                $('#myModal').find(".modal-footer > button[name=btn-save-faculty]").html('Thêm');
                var valueForm = $('form#faculty-form').serialize();
                var url = $(this).attr('data-link');
                $('.form-group').find('span.messageErrors').remove();
                $.ajax({
                    type: "post",
                    url: url,
                    data: valueForm,
                    dataType: 'json',
                    success: function (result) {
                        if (result.status === "fail") {
                            //show error list fields
                            if (result.arrMessages !== undefined) {
                                $.each(result.arrMessages, function (elementName, arrMessagesEveryElement) {
                                    $.each(arrMessagesEveryElement, function (messageType, messageValue) {
                                        $('form#faculty-form').find('.' + elementName).parents('.form-group ').append('<span class="messageErrors" style="color:red">' + messageValue + '</span>');
                                    });
                                });
                            }
                        } else if (result.status === "success") {
                            $('#myModal').find('.modal-body').html('<p>Đã thêm khoa thành công</p>');
                            $("#myModal").find('.modal-footer').html('<button  class="btn btn-default" data-dismiss="modal">Đóng</button>');
                            $('#myModal').on('hidden.bs.modal', function (e) {
                                location.reload();
                            });
                        }
                    }
                });
            });

            $('a#destroy-class').click(function () {
                var id = $(this).attr("data-class-id");
                var url = $(this).attr('data-class-link');
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
                                    swal("Deleted!", "Đã xóa lớp " + data.class.name, "success");
                                    $('.sa-confirm-button-container').click(function () {
                                        location.reload();
                                    })
                                } else {
                                    swal("Cancelled", "Không tìm thấy lớp !!! :)", "error");
                                }
                            }
                        });
                    } else {
                        swal("Đã hủy", "Đã hủy xóa lớp:)", "error");
                    }
                });
            });
//
        });

    </script>
@endsection