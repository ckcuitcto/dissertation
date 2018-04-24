<?php
/**
 * Created by PhpStorm.
 * User: Thai Duc
 * Date: 10-Apr-18
 * Time: 12:41 AM
 */
?>
@extends('layouts.default')
@section('content')
    <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class="fa fa-file-text-o"></i> Danh sách sinh viên</h1>
                <p>Trường Đại học Công nghệ Sài Gòn</p>
            </div>
            <ul class="app-breadcrumb breadcrumb side">
                <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
                <li class="breadcrumb-item">Trang chủ</li>
                <li class="breadcrumb-item active"><a href="#"> Danh sách Sinh viên</a></li>
            </ul>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="tile">

                    <div class="tile-body">
                        <table class="table table-hover table-bordered" id="sampleTable">
                            <thead>
                            <tr>
                                <th>STT</th>
                                <th>Tên</th>
                                <th>Chức vụ</th>
                                <th>Lớp</th>
                                <th>Khoa</th>
                                <th>Khóa</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($users as $key => $student)
                                <tr>
                                    <td> {{ $key +1 }}</td>
                                    <td>{{ $student->name }} </td>
                                    <td>{{ $student->Role->display_name }}</td>
                                    <td>{{ $student->Student->Classes->name or "" }}</td>
                                    <td>{{ $student->Faculty->name }}</td>
                                    <td> {{ $student->Student->academic_year_from  ." - ". $student->Student->academic_year_to }}</td>
                                    <td>
                                        <a data-student-id="{{$student->id}}" id="update-student"
                                           data-student-edit-link="{{route('student-edit',$student->id)}}"
                                           data-student-update-link="{{route('student-update',$student->id)}}">
                                            <i class="fa fa-lg fa-edit" aria-hidden="true"> </i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <div class="row">
                            <div class="col-md-6">
                                <button data-toggle="modal" data-target="#myModal" class="btn btn-primary"
                                        id="btnAddstudent" type="button"><i class="fa fa-pencil-square-o"
                                                                             aria-hidden="true"></i>Thêm
                                </button>
                                <input class="btn btn-success"
                                        id="btnAddstudent" type="button">l
                                </input>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="myModal" role="dialog">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Thêm mới học kì</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span
                                    aria-hidden="true">×</span></button>
                    </div>
                    <div class="modal-body">
                        <form id="student-form">
                            {!! csrf_field() !!}
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-row">
                                        <label for="year">Năm học</label>
                                        <div class="input-group">
                                            <input type="text" class="input-sm form-control year_from" id="year_from" name="year_from"/>
                                            <span class="input-group-addon">to</span>
                                            <input type="text" class="input-sm form-control year_to" name="year_to" id="year_to"/>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <label for="mark_date">Ngày chấm</label>
                                        <div class="input-group">
                                            <input type="text" class="input-sm form-control date_start_to_mark" id="date_start_to_mark" name="date_start_to_mark"/>
                                            <span class="input-group-addon">to</span>
                                            <input type="text" class="input-sm form-control date_end_to_mark" id="date_end_to_mark"  name="date_end_to_mark"/>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <label for="term">Học kì</label>
                                        <input type="number" class="form-control term" name="term" id="term"
                                               placeholder="Học kì">
                                    </div>
                                </div>
                            </div>
                        </form>
                        <div class="modal-footer">
                            <button data-link="{{ route('student-store') }}" class="btn btn-primary"
                                    id="btn-save-student" name="btn-save-student" type="button">
                                Thêm
                            </button>
                            <button class="btn btn-secondary" type="button" data-dismiss="modal">Đóng</button>
                        </div>
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


        $("#year_from").datepicker({format: "yyyy", viewMode: "years", minViewMode: "years", todayBtn: "linked", clearBtn: true, language: "vi",});
        $("#year_to").datepicker({format: "yyyy", viewMode: "years", minViewMode: "years", todayBtn: "linked", clearBtn: true, language: "vi" });
        $('#date_start_to_mark').datepicker({todayBtn: "linked", language: "vi", format: "dd/mm/yyyy", clearBtn: true,});
        $('#date_end_to_mark').datepicker({todayBtn: "linked", language: "vi", format: "dd/mm/yyyy", clearBtn: true,});

        $(document).ready(function () {
            $("a#update-student").click(function () {
                var urlEdit = $(this).attr('data-student-edit-link');
                var urlUpdate = $(this).attr('data-student-update-link');
                var id = $(this).attr('data-student-id');
                $('.form-row').find('span.messageErrors').remove();
                $.ajax({
                    type: "get",
                    url: urlEdit,
                    data: {id: id},
                    dataType: 'json',
                    success: function (result) {
                        if (result.status === true) {
                            if (result.student !== undefined) {
                                $.each(result.student, function (elementName, value) {
//                                    $.each(arrMessagesEveryElement, function (messageType, messageValue) {
//                                    alert(elementName + "+ " + messageValue)
                                    $('.' + elementName).val(value);
//                                    });
                                });
                            }
                        }
                    }
                });
                $('#myModal').find(".modal-title").text('Sửa thông tin khoa');
                $('#myModal').find(".modal-footer > button[name=btn-save-student]").html('Sửa')
                $('#myModal').find(".modal-footer > button[name=btn-save-student]").attr('data-link', urlUpdate);
                $('#myModal').modal('show');
            });

            $("#btn-save-student").click(function () {
                var valueForm = $('form#student-form').serialize();
                var url = $(this).attr('data-link');
                $('.form-row').find('span.messageErrors').remove();
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
                                        $('form#student-form').find('.' + elementName).parents('.form-row').append('<span class="messageErrors" style="color:red">' + messageValue + '</span>');
                                    });
                                });
                            }
                        } else if (result.status === true) {
                            $('#myModal').find('.modal-body').html('<p>Thành công</p>');
                            $("#myModal").find('.modal-footer').html('<button  class="btn btn-default" data-dismiss="modal">Đóng</button>');
                            $('#myModal').on('hidden.bs.modal', function (e) {
                                location.reload();
                            });
                        }
                    }
                });
            });

            $('a#destroy-student').click(function () {
                var id = $(this).attr("data-student-id");
                var url = $(this).attr('data-student-link');
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
                                    swal("Deleted!", "Đã xóa học kì " + data.student.name, "success");
                                    $('.sa-confirm-button-container').click(function () {
                                        location.reload();
                                    })
                                } else {
                                    swal("Cancelled", "Không tìm thấy học kì !!! :)", "error");
                                }
                            }
                        });
                    } else {
                        swal("Đã hủy", "Đã hủy xóa học kì:)", "error");
                    }
                });
            });

            $('#myModal').on('hidden.bs.modal', function (e) {
                $("input[type=text],input[type=number], select").val('');
                $('.text-red').html('');
            });

        });
    </script>
@endsection
