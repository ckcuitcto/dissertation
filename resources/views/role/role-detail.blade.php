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
                <h1><i class="fa fa-file-text-o"></i> Vai trò {{ $role->name }}</h1>
                <p>Trường Đại học Công nghệ Sài Gòn</p>
            </div>
            <ul class="app-breadcrumb breadcrumb side">
                <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
                <li class="breadcrumb-item">Trang chủ</li>
                <li class="breadcrumb-item active"><a href="#"> Vai trò {{$role->name}}</a></li>
            </ul>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="tile">
                    <div class="tile user-settings">
                        <h4> Các quyền của vai trò {{ $role->name  }}</h4>
                    </div>
                    <div class="tile-body">
                        <div class="row">
                            <div class="col-md-6">
                                <b>Danh sách các quyền</b>
                            </div>
                        </div>
                        <table class="table table-hover table-bordered">
                            <thead>
                            <tr>
                                <th>Mã </th>
                                <th>Tên</th>
                                <th>Miêu tả</th>
                                <th>Sửa</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($role->Permissions as $permission)
                                <tr>
                                    <td>{{ $permission->id }}</td>
                                    <td>{{ $permission->name }}</td>
                                    <td>{{ $permission->title }}</td>
                                    <td><a class="btn btn-primary" id="btn-edit-role" href="#"><i class="fa fa-lg fa-edit"></i></a></td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="tile">
                    <div class="tile user-settings">
                        <div class="row">
                            <div class="col-md-6">
                                <b>Danh sách các User thuộc vai trò  {{ $role->name }}</b>
                            </div>
                        </div>
                    </div>
                    <div class="tile-body">
                        <table class="table table-hover table-bordered" id="sampleTable">
                            <thead>
                            <tr>
                                <th>Mã </th>
                                <th>Tên</th>
                                <th>Email</th>
                                <th>Chức vụ</th>
                                <th>Sửa</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($role->Users as $user)
                                <tr>
                                    <td><a href="{{ route('faculty-detail',$user->id) }}">{{ $user->id }} </a>
                                    </td>
                                    <td>
                                        <a href="{{ route('faculty-detail',$user->id) }}">{{ $user->name }} </a>
                                    </td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->Role->name }}</td>
                                    <td><a class="btn btn-primary" id="btn-edit-role" href="#"><i class="fa fa-lg fa-edit"></i></a></td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="modal-edit-class" role="dialog">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Vai trò</h5>
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
                                               value="{{$role->name}}"
                                               aria-describedby="class" placeholder="Nhập tên lớp">
                                        <p style="color:red; display: none;" class="name"></p>
                                    </div>
                                    <div class="form-group">
                                        <label for="staff_id">Cố vấn học tập</label>
                                        <select name="staff_id" id="staff_id" class="staff_id form-control">
                                            @foreach($roles as $value)
                                                <option {{ ($value->id == $role->id) ? "selected" : "" }} value="{{$value->id}}"> {{ $value->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <div class="modal-footer">
                            <button data-link="{{ route('class-update',$role->id) }}" class="btn btn-primary"
                                    id="btn-save-class" name="btn-save-class" type="button">
                                Sửa
                            </button>
                            <button class="btn btn-secondary" type="button" data-dismiss="modal">Đóng</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="modal-edit-class" role="dialog">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Vai trò</h5>
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
                                               value="{{$role->name}}"
                                               aria-describedby="class" placeholder="Nhập tên lớp">
                                        <p style="color:red; display: none;" class="name"></p>
                                    </div>
                                    <div class="form-group">
                                        <label for="staff_id">Cố vấn học tập</label>
                                        <select name="staff_id" id="staff_id" class="staff_id form-control">
                                            @foreach($roles as $value)
                                                <option {{ ($value->id == $role->id) ? "selected" : "" }} value="{{$value->id}}"> {{ $value->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <div class="modal-footer">
                            <button data-link="{{ route('class-update',$role->id) }}" class="btn btn-primary"
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

    <script>
        $(document).ready(function() {
//            $('#sampleTable').DataTable({
//                "language": {
//                    "info": "Showing _START_ to _END_ of _TOTAL_ entries",
//                }
//            })
        } );
    </script>
@endsection