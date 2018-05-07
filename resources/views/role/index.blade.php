@extends('layouts.default')
@section('content')
    <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class="fa fa-file-text-o"></i> Danh sách các vai trò</h1>
                <p>Trường Đại học Công nghệ Sài Gòn</p>
            </div>
            <ul class="app-breadcrumb breadcrumb side">
                <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
                <li class="breadcrumb-item">Trang chủ</li>
                <li class="breadcrumb-item active"><a href="#"> Danh sách vai trò</a></li>
            </ul>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="tile">

                    <div class="tile-body">
                        <table class="table table-hover table-bordered" id="sampleTable">
                            <thead>
                            <tr>
                                <th>Id</th>
                                <th>Vai trò</th>
                                <th>Tên hiển thị</th>
                                <th>Miêu tả</th>
                                <th>Số người</th>
                                <th>Quyền</th>
                                <th>Sửa</th>
                                <th>Xóa</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($roles as $role)
                                <tr>
                                    <td>{{ $role->id }} </td>
                                    <td>{{ $role->name }} </td>
                                    <td>{{ $role->display_name }} </td>
                                    <td>{{ $role->description }} </td>
                                    <td> {{ count($role->Users) }} </td>
                                    <td>
                                        @foreach($role->Permissions as $key => $permission)
                                            @if($key == count($role->Permissions)-1)
                                                <b>{{ $permission->name  }}</b>
                                            @else
                                                <b>{{ $permission->name  }}</b>&nbsp&nbsp {{ " && " }}&nbsp&nbsp
                                            @endif
                                        @endforeach
                                    </td>
                                    <td>
                                        <a data-role-id="{{$role->id}}" id="role-update"
                                           data-role-edit-link="{{route('role-edit',$role->id)}}"
                                           data-role-update-link="{{route('role-update',$role->id)}}">
                                            <i class="fa fa-lg fa-edit" aria-hidden="true"> </i>
                                        </a>
                                    </td>
                                    <td>
                                        @if(!count($role->Users)>0)
                                            <a data-role-id="{{$role->id}}" id="role-destroy"
                                               data-role-link="{{route('role-destroy',$role->id)}}">
                                                <i class="fa fa-lg fa-trash-o" aria-hidden="true"> </i>
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <div class="row">
                            <div class="col-md-6">
                                <button data-toggle="modal" data-target="#myModal" class="btn btn-primary"
                                        id="btnAddrole" type="button"><i class="fa fa-pencil-square-o"
                                                                         aria-hidden="true"></i>Thêm
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="myModal" role="dialog">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Thêm mới vai trò</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span
                                    aria-hidden="true">×</span></button>
                    </div>
                    <div class="modal-body">
                        <form id="role-form">
                            {!! csrf_field() !!}
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="name">Tên vai trò :</label>
                                        <input type="hidden" name="id" class="id" id="idRoleModal">
                                        <input class="form-control name" id="name" name="name" type="text" required
                                               aria-describedby="role">
                                        <p style="color:red; display: none;" class="name"></p>
                                    </div>
                                    <div class="form-group">
                                        <label for="name">Tên hiển thị:</label>
                                        <input class="form-control display_name" id="display_name" name="display_name" type="text" required
                                               aria-describedby="permission">
                                        <p style="color:red; display: none;" class="display_name"></p>
                                    </div>
                                    <div class="form-group">
                                        <label for="name">Miêu tả :</label>
                                        <input class="form-control description" id="description" name="description" type="text" required
                                               aria-describedby="description">
                                        <p style="color:red; display: none;" class="description"></p>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <table class="table table-striped">
                                        <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Quyền</th>
                                            <th scope="col">Miêu tả</th>
                                            <th></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($permissions as $key => $permission)
                                            <tr>
                                                <th scope="row">{{ $key+1 }}</th>
                                                <td>{{ $permission->name }}</td>
                                                <td>{{ $permission->title }}</td>
                                                <td>
                                                    <div class="toggle">
                                                        <label>
                                                            <input type="checkbox" name="permission[]" value="{{$permission->id}}" class="permission_{{ $permission->id }}" id="{{$permission->id}}"><span class="button-indecator"></span>
                                                        </label>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </form>
                        <div class="modal-footer">
                            <button data-link="{{ route('role-store') }}" class="btn btn-primary"
                                    id="btn-save-role" name="btn-save-role" type="button">
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
        $(document).ready(function () {

            $("a#role-update").click(function () {
                var urlEdit = $(this).attr('data-role-edit-link');
                var urlUpdate = $(this).attr('data-role-update-link');
                var id = $(this).attr('data-role-id');
                $('.form-group').find('span.messageErrors').remove();
                $.ajax({
                    type: "get",
                    url: urlEdit,
                    data: {id: id},
                    dataType: 'json',
                    cache: false,
                    success: function (result) {
                        if (result.status === true) {
                            if (result.role !== undefined) {
                                $.each(result.role, function (elementName, value) {
//                                    $.each(arrMessagesEveryElement, function (messageType, messageValue) {
//                                    alert(elementName + "+ " + value);
                                    $('.' + elementName).val(value);
//                                    });
                                    if(elementName === "permissions"){
                                        $.each(value, function (permission, valuePermission) {
//                                            alert(permission + "+ " + valuePermission.id);
                                            $('.permission_' + valuePermission.id).val(valuePermission.id).prop('checked', true);

                                        });
                                    }
                                });
                            }
                        }
                    }
                });
                $('#myModal').find(".modal-title").text('Sửa thông tin vai trò ');
                $('#myModal').find(".modal-footer > button[name=btn-save-role]").html('Sửa')
                $('#myModal').find(".modal-footer > button[name=btn-save-role]").attr('data-link', urlUpdate);
                $('#myModal').modal('show');
            });
            $("#btn-save-role").click(function () {
//                $('#myModal').find(".modal-title").text('Thêm mới Khoa');
//                $('#myModal').find(".modal-footer > button[name=btn-save-role]").html('Thêm');
                var valueForm = $('form#role-form').serialize();
                var url = $(this).attr('data-link');
                $('.form-group').find('span.messageErrors').remove();
                $.ajax({
                    type: "post",
                    url: url,
                    data: valueForm,
                    dataType: 'json',
                    cache: false,
                    success: function (result) {
                        if (result.status === false) {
                            //show error list fields
                            if (result.arrMessages !== undefined) {
                                $.each(result.arrMessages, function (elementName, arrMessagesEveryElement) {
                                    $.each(arrMessagesEveryElement, function (messageType, messageValue) {
                                        $('form#role-form').find('.' + elementName).parents('.form-group ').append('<span class="messageErrors" style="color:red">' + messageValue + '</span>');
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

            $('a#role-destroy').click(function () {
                var id = $(this).attr("data-role-id");
                var url = $(this).attr('data-role-link');
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
                                    swal("Deleted!", "Đã xóa vai trò " + data.role.name, "success");
                                    $('.sa-confirm-button-container').click(function () {
                                        location.reload();
                                    })
                                } else {
                                    swal("Cancelled", "Không tìm thấy Vai trò !!! :)", "error");
                                }
                            }
                        });
                    } else {
                        swal("Đã hủy", "Đã hủy xóa vai trò:)", "error");
                    }
                });
            });

            $('#myModal').on('hidden.bs.modal', function (e) {
                $("input[type=text],input[type=number], select").val('');
                $("input[type=checkbox]").prop('checked', false);
                $('.text-red').html('');
                $('.form-group').find('span.messageErrors').remove();
            });

        });
    </script>
@endsection
