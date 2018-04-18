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
                                <th>Khoa</th>
                                <th>Số lượng lớp</th>
                                <th></th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($faculties as $faculty)
                                <tr>
                                    <td><a href="{{ route('faculty-detail',$faculty->id) }}">{{ $faculty->name }} </a>
                                    </td>
                                    <td>{{ count($faculty->classes) }}</td>
                                    <td>
                                        <a data-faculty-id="{{$faculty->id}}" id="faculty-update"
                                           data-faculty-edit-link="{{route('faculty-edit',$faculty->id)}}"
                                           data-faculty-update-link="{{route('faculty-update',$faculty->id)}}">
                                            <i class="fa fa-lg fa-edit" aria-hidden="true"> </i>
                                        </a>
                                    </td>
                                    <td>
                                        @if(!count($faculty->classes)>0)
                                            <a data-faculty-id="{{$faculty->id}}" id="faculty-destroy"
                                               data-faculty-link="{{route('faculty-destroy',$faculty->id)}}">
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
                                        id="btnAddFaculty" type="button"><i class="fa fa-pencil-square-o"
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
                        <h5 class="modal-title">Thêm mới khoa</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span
                                    aria-hidden="true">×</span></button>
                    </div>
                    <div class="modal-body">

                        <form id="faculty-form">
                            {!! csrf_field() !!}
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="name">Tên khoa :</label>
                                        <input type="hidden" name="id" class="id" id="idFacultyModal">
                                        <input class="form-control name" id="name" name="name" type="text" required
                                               aria-describedby="faculty" placeholder="Nhập tên khoa">
                                        <p style="color:red; display: none;" class="name"></p>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <div class="modal-footer">
                            <button data-link="{{ route('faculty-store') }}" class="btn btn-primary"
                                    id="btn-save-faculty" name="btn-save-faculty" type="button">
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
        $(document).ready(function () {
            $("a#faculty-update").click(function () {
                var urlEdit = $(this).attr('data-faculty-edit-link');
                var urlUpdate = $(this).attr('data-faculty-update-link');
                var id = $(this).attr('data-faculty-id');
                $('.form-group').find('span.messageErrors').remove();
                $.ajax({
                    type: "get",
                    url: urlEdit,
                    data: {id: id},
                    dataType: 'json',
                    success: function (result) {
                        if (result.status === true) {
                            if (result.faculty !== undefined) {
                                $.each(result.faculty, function (elementName, value) {
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
                $('#myModal').find(".modal-footer > button[name=btn-save-faculty]").html('Sửa')
                $('#myModal').find(".modal-footer > button[name=btn-save-faculty]").attr('data-link',urlUpdate);
                $('#myModal').modal('show');
            });

            $("#btn-save-faculty").click(function () {
                var valueForm = $('form#faculty-form').serialize();
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
                                        $('form#faculty-form').find('.' + elementName).parents('.form-group ').append('<span class="messageErrors" style="color:red">' + messageValue + '</span>');
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

            $('a#faculty-destroy').click(function () {
                var id = $(this).attr("data-faculty-id");
                var url = $(this).attr('data-faculty-link');
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
                                    swal("Deleted!", "Đã xóa Khoa " + data.faculty.name, "success");
                                    $('.sa-confirm-button-container').click(function () {
                                        location.reload();
                                    })
                                } else {
                                    swal("Cancelled", "Không tìm thấy Khoa !!! :)", "error");
                                }
                            }
                        });
                    } else {
                        swal("Đã hủy", "Đã hủy xóa khoa:)", "error");
                    }
                });
            });

        });
    </script>
@endsection
