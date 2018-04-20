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
                <h1><i class="fa fa-file-text-o"></i> Danh sách các Khoa</h1>
                <p>Trường Đại học Công nghệ Sài Gòn</p>
            </div>
            <ul class="app-breadcrumb breadcrumb side">
                <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
                <li class="breadcrumb-item">Trang chủ</li>
                <li class="breadcrumb-item active"><a href="#"> Danh sách Khoa</a></li>
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
                                <th>Học kì</th>
                                <th>Năm học</th>
                                <th>Ngày bắt đầu chấm</th>
                                <th>Ngày kết thúc chấm</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($semesters as $key => $semester)
                                <tr>
                                    <td> {{ $key +1 }}</td>
                                    <td>{{ $semester->term }} </td>
                                    <td>{{ $semester->year_from . "-" . $semester->year_to }}</td>
                                    <td>{{ $semester->date_start_to_mark }}</td>
                                    <td>{{ $semester->date_end_to_mark }}</td>
                                    <td>
                                        <a data-semester-id="{{$semester->id}}" id="update-semester"
                                           data-semester-edit-link="{{route('semester-edit',$semester->id)}}"
                                           data-semester-update-link="{{route('semester-update',$semester->id)}}">
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
                                        id="btnAddsemester" type="button"><i class="fa fa-pencil-square-o"
                                                                             aria-hidden="true"></i>Thêm
                                </button>
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
                        <form id="semester-form">
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
                            <button data-link="{{ route('semester-store') }}" class="btn btn-primary"
                                    id="btn-save-semester" name="btn-save-semester" type="button">
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
    <script type="text/javascript">$('#sampleTable').DataTable();</script>


    <script>


        $("#year_from").datepicker({format: "yyyy", viewMode: "years", minViewMode: "years", todayBtn: "linked", clearBtn: true, language: "vi",});
        $("#year_to").datepicker({format: "yyyy", viewMode: "years", minViewMode: "years", todayBtn: "linked", clearBtn: true, language: "vi" });
        $('#date_start_to_mark').datepicker({todayBtn: "linked", language: "vi", format: "dd/mm/yyyy", clearBtn: true,});
        $('#date_end_to_mark').datepicker({todayBtn: "linked", language: "vi", format: "dd/mm/yyyy", clearBtn: true,});

        $(document).ready(function () {
            $("a#update-semester").click(function () {
                var urlEdit = $(this).attr('data-semester-edit-link');
                var urlUpdate = $(this).attr('data-semester-update-link');
                var id = $(this).attr('data-semester-id');
                $('.form-row').find('span.messageErrors').remove();
                $.ajax({
                    type: "get",
                    url: urlEdit,
                    data: {id: id},
                    dataType: 'json',
                    success: function (result) {
                        if (result.status === true) {
                            if (result.semester !== undefined) {
                                $.each(result.semester, function (elementName, value) {
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
                $('#myModal').find(".modal-footer > button[name=btn-save-semester]").html('Sửa')
                $('#myModal').find(".modal-footer > button[name=btn-save-semester]").attr('data-link', urlUpdate);
                $('#myModal').modal('show');
            });

            $("#btn-save-semester").click(function () {
                var valueForm = $('form#semester-form').serialize();
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
                                        $('form#semester-form').find('.' + elementName).parents('.form-row').append('<span class="messageErrors" style="color:red">' + messageValue + '</span>');
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

            $('a#destroy-semester').click(function () {
                var id = $(this).attr("data-semester-id");
                var url = $(this).attr('data-semester-link');
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
                                    swal("Deleted!", "Đã xóa học kì " + data.semester.name, "success");
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

        });
    </script>
@endsection
