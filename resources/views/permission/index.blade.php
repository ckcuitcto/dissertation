@extends('layouts.default')
@section('content')
    <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class="fa fa-file-text-o"></i> Danh sách các quyền</h1>
                <p>Trường Đại học Công nghệ Sài Gòn</p>
            </div>
            <ul class="app-breadcrumb breadcrumb side">
                <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
                <li class="breadcrumb-item">Trang chủ</li>
                <li class="breadcrumb-item active"><a href="#"> Danh sách quyền</a></li>
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
                                <th>Tên</th>
                                <th>Tên hiển thị</th>
                                <th>Miêu tả</th>
                                <th>Sửa</th>
                                <th>Xóa</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($permissions as $permission)
                                <tr>
                                    <td>{{ $permission->id }} </td>
                                    <td>{{ $permission->name }} </td>
                                    <td>{{ $permission->display_name }} </td>
                                    <td>{{ $permission->description }} </td>
                                    <td>
                                        <a data-permission-id="{{$permission->id}}" id="permission-update"
                                           data-permission-edit-link="{{route('permission-edit',$permission->id)}}"
                                           data-permission-update-link="{{route('permission-update',$permission->id)}}">
                                            <i class="fa fa-lg fa-edit" aria-hidden="true"> </i>
                                        </a>
                                    </td>
                                    <td>
                                        @if(!count($permission->Roles)>0)
                                            <a data-permission-id="{{$permission->id}}" id="permission-destroy"
                                               data-permission-link="{{route('permission-destroy',$permission->id)}}">
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
                                        id="btnAddpermission" type="button"><i class="fa fa-pencil-square-o"
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
                        <h5 class="modal-title">Thêm mới quyền</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span
                                    aria-hidden="true">×</span></button>
                    </div>
                    <div class="modal-body">
                        <form id="permission-form">
                            {!! csrf_field() !!}
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="name">Quyền :</label>
                                        <input type="hidden" name="id" class="id" id="idpermissionModal">
                                        <input class="form-control name" id="name" name="name" type="text" required
                                               aria-describedby="permission">
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
                            </div>
                        </form>
                        <div class="modal-footer">
                            <button data-link="{{ route('permission-store') }}" class="btn btn-primary"
                                    id="btn-save-permission" name="btn-save-permission" type="button">
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
    <!--<script type="text/javascript">$('#sampleTable').DataTable();</script>-->


    <script>
        $(document).ready(function () {

            $("a#permission-update").click(function () {
                var urlEdit = $(this).attr('data-permission-edit-link');
                var urlUpdate = $(this).attr('data-permission-update-link');
                var id = $(this).attr('data-permission-id');
                $('.form-row').find('span.messageErrors').remove();
                $.ajax({
                    type: "get",
                    url: urlEdit,
                    data: {id: id},
                    dataType: 'json',
                    success: function (result) {
                        if (result.status === true) {
                            if (result.permission !== undefined) {
                                $.each(result.permission, function (elementName, value) {
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
                $('#myModal').find(".modal-footer > button[name=btn-save-permission]").html('Sửa')
                $('#myModal').find(".modal-footer > button[name=btn-save-permission]").attr('data-link', urlUpdate);
                $('#myModal').modal('show');
            });

            $("#btn-save-permission").click(function () {
                var valueForm = $('form#permission-form').serialize();
                var url = $(this).attr('data-link');
                $('.form-group').find('span.messageErrors').remove();
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
//                                    $.each(arrMessagesEveryElement, function (messageType, messageValue) {
//                                    alert(elementName + "+ " + messageValue)
                                        $('form#permission-form').find('.' + elementName).parents('.form-group ').append('<span class="messageErrors" style="color:red">' + messageValue + '</span>');
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

            $('a#permission-destroy').click(function () {
                var id = $(this).attr("data-permission-id");
                var url = $(this).attr('data-permission-link');
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
                                    swal("Deleted!", "Đã xóa quyền " + data.permission.name, "success");
                                    $('.sa-confirm-button-container').click(function () {
                                        location.reload();
                                    })
                                } else {
                                    swal("Cancelled", "Không tìm thấy quyền !!! :)", "error");
                                }
                            }
                        });
                    } else {
                        swal("Đã hủy", "Đã hủy xóa quyền:)", "error");
                    }
                });
            });

            $('#myModal').on('hidden.bs.modal', function (e) {
                $("input[type=text],input[type=number], select").val('');
                $('.text-red').html('');
                $('.form-group').find('span.messageErrors').remove();
            });

        });
    </script>
@endsection
