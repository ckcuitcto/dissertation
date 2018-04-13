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
                <h1><i class="fa fa-file-text-o"></i> Lớp {{ $class->name }}</h1>
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

                    <div class="tile user-settings">
                        <h4 class="line-head">Thông tin lớp {{ $class->name }}</h4>
                        <div class="row">
                            <div class="col-md-6">
                                <div>Sỉ số : {{ count($class->Students) }}</div>
                                <div>Cố vấn học tập : {{ $class->Staff->User->name }}</div>
                            </div>
                            <div class="col-md-6">
                                <div>- Khoa: {{ $class->Faculty->name }}</div>
                                <div>
                                    <button data-toggle="modal" data-target="#modal-edit-class" class="btn btn-primary"
                                            id="btn-edit-class" type="button"><i class="fa fa-pencil-square-o"
                                                                                 aria-hidden="true"></i>Sửa
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tile-body">
                        <table class="table table-hover table-bordered" id="sampleTable">
                            <thead>
                            <tr>
                                <th>MSSV</th>
                                <th>Sinh viên</th>
                                <th>Email</th>
                                <th>SĐT</th>
                                <th>Giới tính</th>
                                <th>Địa chỉ</th>
                                <th>Ngày sinh</th>
                                <th>Chức vụ</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($class->Students as $student)
                                <tr>
                                    <td><a href="{{ route('faculty-detail',$student->id) }}">{{ $student->user_id}} </a>
                                    </td>
                                    <td>
                                        <a href="{{ route('faculty-detail',$student->id) }}">{{ $student->User->name }} </a>
                                    </td>
                                    <td>{{ $student->User->email }}</td>
                                    <td>{{ $student->User->phone_number }}</td>
                                    <td>{{ $student->User->gender }}</td>
                                    <td>{{ $student->User->address }}</td>
                                    <td>{{ $student->User->birthday }}</td>
                                    <td>{{ $student->User->Role->name }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <div class="row">
                            <div class="col-md-6">
                                <button data-toggle="modal" data-target="#myModal" class="btn btn-primary"
                                        id="btn-add-student" type="button"><i class="fa fa-pencil-square-o"
                                                                              aria-hidden="true"></i>Thêm sinh viên
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="modal-edit-class" role="dialog">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Sửa thông tin lớp</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span
                                    aria-hidden="true">×</span></button>
                    </div>
                    <div class="modal-body">
                        <form id="class-form">
                            {!! csrf_field() !!}
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="name">Lớp khoa :</label>
                                        <input type="hidden" name="id" class="id" id="modal-class-edit">
                                        <input class="form-control name" id="name" name="name" type="text" required
                                               value="{{$class->name}}"
                                               aria-describedby="class" placeholder="Nhập tên lớp">
                                        <p style="color:red; display: none;" class="name"></p>
                                    </div>
                                    <div class="form-group">
                                        <label for="staff_id">Cố vấn học tập</label>
                                        <select name="staff_id" id="staff_id" class="staff_id form-control">
                                            @foreach($staff as $value)
                                                <option {{ ($value->id == $class->Staff->user_id) ? "selected" : "" }} value="{{$value->id}}"> {{ $value->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <div class="modal-footer">
                            <button data-link="{{ route('class-update',$class->id) }}" class="btn btn-primary"
                                    id="btn-save-class" name="btn-save-class" type="button">
                                Sửa
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
    <script type="text/javascript">$('#sampleTable').DataTable();</script>

    <script>
        $(document).ready(function () {
            $("#btn-save-class").click(function () {
                var valueForm = $('form#class-form').serialize();
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
                                        $('form#class-form').find('.' + elementName).parents('.form-group ').append('<span class="messageErrors" style="color:red">' + messageValue + '</span>');
                                    });
                                });
                            }
                        } else if (result.status === "success") {
                            $('#modal-edit-class').find('.modal-body').html('<p>Đã sửa thành công</p>');
                            $("#modal-edit-class").find('.modal-footer').html('<button  class="btn btn-default" data-dismiss="modal">Đóng</button>');
                            $('#modal-edit-class').on('hidden.bs.modal', function (e) {
                                location.reload();
                            });
                        }
                    }
                });
            });
        });
    </script>
@endsection