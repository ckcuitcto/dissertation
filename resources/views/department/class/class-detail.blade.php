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
                <li class="breadcrumb-item active"><a href="#"> Danh sách Sinh viên của lớp {{ $class->name }}</a></li>
            </ul>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="tile">

                    <div class="tile user-settings">
                        <h4 class="line-head">Thông tin lớp {{ $class->name }}</h4>
                        <div class="row">
                            <div class="col-md-6">
                                <div>- Sỉ số : {{ count($class->Students) }}</div>
                                <div>- Cố vấn học tập : {{ $class->Staff->User->name or "" }}</div>
                            </div>
                            <div class="col-md-6">
                                <div>- Khoa: {{ $class->Faculty->name }}</div>
                                <div style="float:right">
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
                                <th>Trạng thái</th>
                                <th>Tác vụ</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($class->Students as $student)
                                <tr>
                                    <td>{{ $student->user_id}}</td>
                                    <td>{{ $student->User->name }}</td>
                                    <td>{{ $student->User->email }}</td>
                                    <td>{{ $student->User->phone_number }}</td>
                                    <td>{{ \App\Http\Controllers\Controller::getDisplayGender( $student->User->gender) }}</td>
                                    <td>{{ $student->User->address }}</td>
                                    <td>{{ $student->User->birthday }}</td>
                                    <td>{{ $student->User->Role->name }}</td>
                                    <td>{{ \App\Http\Controllers\Controller::getDisplayStatusStudent($student->status) }}</td>
                                    <td>
                                        <button data-student-id="{{$student->id}}" class="btn update-student btn-primary"
                                                data-student-edit-link="{{route('student-edit',$student->id)}}"
                                                data-student-update-link="{{route('student-update',$student->id)}}">
                                            <i class="fa fa-lg fa-edit" aria-hidden="true"> </i> Sửa
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        {{--<div class="row">--}}
                            {{--<div class="col-md-6">--}}
                                {{--<button data-toggle="modal" data-target="#myModal" class="btn btn-primary"--}}
                                        {{--id="btn-add-student" type="button"><i class="fa fa-pencil-square-o"--}}
                                                                              {{--aria-hidden="true"></i>Thêm sinh viên--}}
                                {{--</button>--}}
                            {{--</div>--}}
                        {{--</div>--}}
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

        <div class="modal fade" id="modal-edit-student" role="dialog">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Sửa thông tin sinh viên</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span
                                    aria-hidden="true">×</span></button>
                    </div>
                    <div class="modal-body">
                        <form id="student-form" method="post" class="form-horizontal">
                            {{ csrf_field() }}
                            <input type="hidden" name="id" class="id">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <label class="control-label col-md-3">Tên</label>
                                        <div class="col-md-8">
                                            <input class="form-control name" type="text" name="name"
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
                                            {{--<input class="form-control email" disabled type="email"--}}
                                                   {{--placeholder="Enter email address">--}}
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="control-label col-md-3">Địa chỉ</label>
                                        <div class="col-md-8">
                                                <textarea class="form-control address" rows="4" name="address"
                                                          placeholder="Enter your address"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <label class="control-label col-md-3">Trạng thái</label>
                                        <div class="col-md-8">
                                            <select name="studentStatus" class="form-control studentStatus">
                                                <option value="{{ STUDENT_STUDYING }}"> Đang học</option>
                                                <option value="{{ STUDENT_DEFERMENT }}"> Bảo lưu</option>
                                                <option value="{{ STUDENT_DROP_OUT }}"> Bỏ học</option>
                                                <option value="{{ STUDENT_GRADUATE }}"> Tốt nghiệp</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="control-label col-md-3">Giới tính</label>
                                        <div class="col-md-9">
                                            <div class="form-check">
                                                <label class="form-check-label">
                                                    <input class="form-check-input gender" type="radio"
                                                           name="gender" value="{{ 1 }}">Nam
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <label class="form-check-label">
                                                    <input class="form-check-input gender" type="radio" value="{{ 0 }}"
                                                           name="gender">Nữ
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-md-8 col-md-offset-3">
                                            <label class="form-check-label">
                                                <div class="toggle">
                                                    <label>
                                                        <input type="checkbox" name="changePassword" id="checkBoxChangePassword"><span class="button-indecator">Đổi mật khẩu</span>
                                                    </label>
                                                </div>
                                            </label>
                                        </div>
                                    </div>
                                    <div id="changepassword" style="display: none;">
                                    <div class="form-group row">
                                        <label class="control-label col-md-3">Mật khẩu mới</label>
                                        <div class="col-md-8">
                                            <input class="form-control password" type="password" name="password" disabled>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="control-label col-md-3">Nhập lại mật khẩu</label>
                                        <div class="col-md-8">
                                            <input class="form-control rePassword" type="password" name="rePassword" disabled>
                                        </div>
                                    </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <div class="modal-footer">
                            <button class="btn btn-primary" id="btn-save-student" name="btn-save-student" type="button"> Sửa
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
            $("input#checkBoxChangePassword").change(function () {
                if($(this).val() === 'on'){
                    $(this).val('off');
                    $("input[type=password]").prop('disabled',false);
                    $("div#changepassword").show();
                }else{
                    $(this).val('on');
                    $("input[type=password]").prop('disabled',true);
                    $("div#changepassword").hide();

                }
            });
            
            $("#btn-save-class").click(function () {
                var valueForm = $('form#class-form').serialize();
                var url = $(this).attr('data-link');
                $('#modal-edit-class').find('span.messageErrors').remove();
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

            $('body').on('click', 'button.update-student', function (e) {
            // $("button.update-student").click(function () {
                var urlEdit = $(this).attr('data-student-edit-link');
                var urlUpdate = $(this).attr('data-student-update-link');
                var id = $(this).attr('data-student-id');
                $('#modal-edit-student').find('span.messageErrors').remove();
                $.ajax({
                    type: "get",
                    url: urlEdit,
                    cache: false,
                    dataType: 'json',
                    success: function (result) {
                        if (result.status === true) {
                            if (result.student !== undefined) {
                                $.each(result.student, function (elementName, value) {
                                    if(elementName === 'gender'){
                                        $('#modal-edit-student').find("input[name=gender][value=" + value + "]").attr('checked', 'checked');
                                    }else if(elementName === 'studentStatus' || elementName === 'role_id' ){
                                        $('#modal-edit-student').find("select."+elementName).val(value);
                                    }
                                    else{
                                        $('#modal-edit-student').find('.' + elementName).val(value);
                                    }
                                });
                            }
                        }
                    }
                });
                $('#modal-edit-student').find(".modal-title").text('Sửa thông sinh viên');
                $('#modal-edit-student').find(".modal-footer > button[name=btn-save-student]").html('Sửa');
                $('#modal-edit-student').find(".modal-footer > button[name=btn-save-student]").attr('data-link', urlUpdate);
                $('#modal-edit-student').modal('show');
            });

            $("#btn-save-student").click(function () {
                var valueForm = $('form#student-form').serialize();
                var url = $(this).attr('data-link');
                $('#modal-edit-student').find('span.messageErrors').remove();

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
                                        $('form#student-form').find('.' + elementName).parents('.form-group').append('<span class="messageErrors" style="color:red">' + messageValue + '</span>');
                                    });
                                });
                            }
                        } else if (result.status === true) {
                            $('#modal-edit-student').find('.modal-body').html('<p>Thành công</p>');
                            $('#modal-edit-student').find('.modal-footer').html('<button  class="btn btn-default" data-dismiss="modal">Đóng</button>');
                            $('#modal-edit-student').on('hidden.bs.modal', function (e) {
                                location.reload();
                            });
                        }
                    }
                });
            });

        });
    </script>
@endsection