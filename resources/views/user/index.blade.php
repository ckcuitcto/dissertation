<?php
/**
 * Created by PhpStorm.
 * User: huynh
 * Date: 29-May-18
 * Time: 7:09 PM
 */
?>
@extends('layouts.default')
@section('content')
    <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class="fa fa-file-text-o"></i> Quản lí tài khoản </h1>
                <p>Trường Đại học Công nghệ Sài Gòn</p>
            </div>
            <ul class="app-breadcrumb breadcrumb side">
                <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
                <li class="breadcrumb-item">Trang chủ</li>
                <li class="breadcrumb-item active"><a href="#"> Danh sách tài khoản</a></li>
            </ul>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="tile">
                    <div class="tile-body">
                        <table class="table table-hover table-bordered">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Tên</th>
                                <th>Chức vụ</th>
                                <th>Trạng thái</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($users as $user)
                                <tr>
                                    <td>{{ $user->id }}</td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->Role->display_name }}</td>
                                    <td>{{ \App\Http\Controllers\Controller::getDisplayStatusUser($user->status) }}</td>
                                    <td>
                                        <button data-user-id="{{$user->id}}" class="btn update-user"
                                                data-user-edit-link="{{route('user-edit',$user->id)}}"
                                                data-user-update-link="{{route('user-update',$user->id)}}">
                                            <i class="fa fa-lg fa-edit" aria-hidden="true"> </i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        {{ $users->links('vendor.pagination.bootstrap-4') }}
                        <div class="row">
                            <div class="col-md-6">
                                <button data-toggle="modal" data-target="#modal-add-user" class="btn btn-primary"
                                        id="btn-add-user" type="button"><i class="fa fa-pencil-square-o"
                                                                           aria-hidden="true"></i>Thêm tài khoản
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="modal-edit-user" role="dialog">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"> Thêm tài khoản</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                    </div>
                    <div class="modal-body">
                        <form id="user-form" method="post" class="form-horizontal">
                            {{ csrf_field() }}
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <label class="control-label col-md-3">Mã</label>
                                        <div class="col-md-8">
                                            <input class="form-control id" type="text" name="id" readonly>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="control-label col-md-3">Trạng thái</label>
                                        <div class="col-md-8">
                                            <select name="status" class="form-control status">
                                                <option value="{{ USER_ACTIVE }}"> Hoạt động</option>
                                                <option value="{{ USER_INACTIVE }}">Ngừng hoạt động</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <label class="control-label col-md-3">Tên</label>
                                        <div class="col-md-8">
                                            <input class="form-control name" type="text" name="name" readonly
                                                   placeholder="Enter full name">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="control-label col-md-3">Chức vụ</label>
                                        <div class="col-md-8">
                                            <select name="role_id" id="role_id" class="form-control role_id">
                                                @foreach($roles as $value)
                                                    <option value="{{ $value->id }}"> {{ $value->display_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <div class="modal-footer">
                            <button class="btn btn-primary" id="btn-save-user" name="btn-save-user" type="button">
                                Sửa
                            </button>
                            <button class="btn btn-secondary" type="button" data-dismiss="modal">Đóng</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="modal-add-user" role="dialog">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"> Thêm tài khoản</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                    </div>
                    <div class="modal-body">
                        <form id="user-add-form" method="post" class="form-horizontal">
                            {{ csrf_field() }}
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <label class="control-label col-md-3">Mã</label>
                                        <div class="col-md-8">
                                            <input class="form-control id" type="text" name="id">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="control-label col-md-3">Trạng thái</label>
                                        <div class="col-md-8">
                                            <select name="status" class="form-control status">
                                                <option value="{{ USER_ACTIVE }}"> Hoạt động</option>
                                                <option value="{{ USER_INACTIVE }}">Ngừng hoạt động</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="control-label col-md-3">Email</label>
                                        <div class="col-md-8">
                                            <input class="form-control email" type="email" name="email">
                                        </div>
                                    </div>
                                    <div class="form-group row" id="faculty">
                                        <label class="control-label col-md-3">Khoa</label>
                                        <div class="col-md-8">
                                            <select name="faculty_id" id="faculty_id" class="form-control faculty_id">
                                                @foreach($faculties as $value)
                                                    <option value="{{ $value->id }}"> {{ $value->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <label class="control-label col-md-3">Tên</label>
                                        <div class="col-md-8">
                                            <input class="form-control name" type="text" name="name">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="control-label col-md-3">Chức vụ</label>
                                        <div class="col-md-8">
                                            <select name="role_id" id="role_id" class="form-control role_id">
                                                @foreach($roles as $value)
                                                    <option value="{{ $value->id }}"> {{ $value->display_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="control-label col-md-3">Giới tính</label>
                                        <div class="col-md-8">
                                            <div class="form-check">
                                                <label class="form-check-label">
                                                    <input class="form-check-input gender" type="radio" checked
                                                           name="gender" value="{{ MALE }}">Nam
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <label class="form-check-label">
                                                    <input class="form-check-input gender" type="radio" value="{{ FEMALE }}"
                                                           name="gender">Nữ
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row" id="classes">
                                        <label class="control-label col-md-3">Lớp</label>
                                        <div class="col-md-8">
                                            <select name="classes_id" id="classes_id" class="form-control classes_id">
                                            </select>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </form>
                        <div class="modal-footer">
                            <button data-link="{{ route('user-store') }}" class="btn btn-primary" id="btn-add" name="btn-add" type="button">
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
    {{--<script type="text/javascript">$('#sampleTable').DataTable();</script>--}}

    <script>
        $(document).ready(function () {
            $("button.update-user").click(function () {
                var urlEdit = $(this).attr('data-user-edit-link');
                var urlUpdate = $(this).attr('data-user-update-link');
                var id = $(this).attr('data-user-id');

                var modal = $('#modal-edit-user');
                modal.find('span.messageErrors').remove();
                $.ajax({
                    type: "get",
                    url: urlEdit,
                    cache: false,
                    dataType: 'json',
                    success: function (result) {
                        if (result.status === true) {
                            if (result.user !== undefined) {
                                $.each(result.user, function (elementName, value) {
                                    if (elementName === 'status' || elementName === 'role_id') {
                                        modal.find("select." + elementName).val(value);
                                    }
                                    else {
                                        modal.find('.' + elementName).val(value);
                                    }
                                });
                            }
                        }
                    }
                });
                modal.find(".modal-title").text('Sửa thông sinh viên');
                modal.find(".modal-footer > button[name=btn-save-user]").html('Sửa');
                modal.find(".modal-footer > button[name=btn-save-user]").attr('data-link', urlUpdate);
                modal.modal('show');
            });
            $("#btn-save-user").click(function () {
                var valueForm = $('form#user-form').serialize();
                var url = $(this).attr('data-link');
                $('#modal-edit-user').find('span.messageErrors').remove();

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
                                        $('form#user-form').find('.' + elementName).parents('.form-group').append('<span class="messageErrors" style="color:red">' + messageValue + '</span>');
                                    });
                                });
                            }
                        } else if (result.status === true) {
                            $('#modal-edit-user').find('.modal-body').html('<p>Thành công</p>');
                            $('#modal-edit-user').find('.modal-footer').html('<button  class="btn btn-default" data-dismiss="modal">Đóng</button>');
                            $('#modal-edit-user').on('hidden.bs.modal', function (e) {
                                location.reload();
                            });
                        }
                    }
                });
            });

            $("button#btn-add").click(function () {
                var valueForm = $('form#user-add-form').serialize();
                var url = $(this).attr('data-link');
                $('form#user-add-form').find('span.messageErrors').remove();
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
                                        $('form#user-add-form').find('.' + elementName).parents('.col-md-8').append('<span class="messageErrors" style="color:red">' + messageValue + '</span>');
                                    });
                                });
                            }
                        } else if (result.status === true) {
                            $('#modal-add-user').find('.modal-body').html('<p>Thành công</p>');
                            $('#modal-add-user').find('.modal-footer').html('<button  class="btn btn-default" data-dismiss="modal">Đóng</button>');
                            $('#modal-add-user').on('hidden.bs.modal', function (e) {
                                location.reload();
                            });
                        }
                    }
                });
            });




            // ẩn hiện khi chọn role
            $('div#modal-add-user').on('change', 'select.role_id', function (e) {
                var roleId = $(this).val();
                if(roleId === "{{ ROLE_PHONGCONGTACSINHVIEN }}" || roleId === "{{ ROLE_ADMIN }}"){
                    $("div#faculty").hide();
                    $("div#classes").hide();
                    $('div#modal-add-user').find("select.faculty_id").prop('disabled',true);
                    $('div#modal-add-user').find("select.classes_id").prop('disabled',true);
                }else if(roleId === "{{ ROLE_BANCANSULOP }}" || roleId === "{{ ROLE_SINHVIEN }}"){
                    $("div#faculty").show();
                    $("div#classes").show();
                    $('div#modal-add-user').find("select.faculty_id").prop('disabled',false);
                    $('div#modal-add-user').find("select.classes_id").prop('disabled',false);
                }else{
                    $("div#faculty").show();
                    $("div#classes").hide();
                    $('div#modal-add-user').find("select.faculty_id").prop('disabled',false);
                    $('div#modal-add-user').find("select.classes_id").prop('disabled',true);
                }
            });

            $('div#modal-add-user').on('change', 'select.faculty_id', function (e) {
                var facultyId = $(this).val();
                var url = "{{ route('class-get-list-by-faculty') }}";
                $.ajax({
                    type: "post",
                    url: url,
                    data: { id: facultyId},
                    dataType: 'json',
                    success: function (data) {
                        $('div#modal-add-user').find("select.classes_id").empty();
                        $.each(data.classes, function (key, value) {
                            $('div#modal-add-user').find("select.classes_id").append('<option value="' + value.id + '">' + value.name + '</option>');
                        });
                    }
                });
            });

            getClassByFacultyId();
            function getClassByFacultyId() {
                var facultyId = $('div#modal-add-user').find('select.faculty_id').val();
                var url = "{{ route('class-get-list-by-faculty') }}";
                $.ajax({
                    type: "post",
                    url: url,
                    data: { id: facultyId},
                    dataType: 'json',
                    success: function (data) {
                        $('div#modal-add-user').find("select.classes_id").empty();
                        $.each(data.classes, function (key, value) {
                            $('div#modal-add-user').find("select.classes_id").append('<option value="' + value.id + '">' + value.name + '</option>');
                        });
                    }
                });
            }
        });

        hideAndShowFaculty();
        function hideAndShowFaculty() {
            var roleId = $('div#modal-add-user').find('select.role_id').val();
            if(roleId === "{{ ROLE_PHONGCONGTACSINHVIEN }}" || roleId === "{{ ROLE_ADMIN }}"){
                $("div#faculty").hide();
            }else{
                $("div#faculty").show();

            }
        }




    </script>
@endsection