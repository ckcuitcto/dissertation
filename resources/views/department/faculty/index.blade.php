@extends('layouts.default')
@section('content')
    <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class="fa fa-file-text-o"></i> Danh sách các Khoa</h1>
                <p>Trường Đại học Công nghệ Sài Gòn</p>
            </div>
            <ul class="app-breadcrumb breadcrumb side">
                <li class="breadcrumb-item"><a href="{{ route('home')}}"><i class="fa fa-home fa-lg"></i></a></li>
                <li class="breadcrumb-item active"> Danh sách Khoa</li>
            </ul>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="tile">
                    <div class="tile-body">
                        <table class="table table-hover table-bordered" id="facultiesTable">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Khoa</th>
                                <th>Số lượng lớp</th>
                                <th>Tác vụ</th>
                            </tr>
                            </thead>
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
    <script type="text/javascript" src="{{ asset('template/js/plugins/bootstrap-notify.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('template/js/plugins/sweetalert.min.js') }}"></script>

    <script>
        $(document).ready(function () {
            var oTable = $('#facultiesTable').DataTable({
                "processing": true,
                "serverSide": true,
                "autoWidth": false,
                "ajax": {
                    "url": "{{ route('ajax-get-faculties') }}",
                    "dataType": "json",
                    "type": "POST",
                    "data": {
                        "_token": "{{ csrf_token() }}"
                    }
                },
                "columns": [
                    {data: "id", name: "id"},
                    {data: "name", name: "name"},
                    {data: "countClass", name: "countClass", searchable: false},
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                ],
                "language": {
                    "lengthMenu": "Hiển thị _MENU_ bản ghi mỗi trang",
                    // "zeroRecords": "Không có bản ghi nào!",
                    // "info": "Hiển thị trang _PAGE_ của _PAGES_",
                    "infoEmpty": "Không có bản ghi nào!!!",
                    "infoFiltered": "(Đã lọc từ _MAX_ total bản ghi)"
                },
                "pageLength": 25
            });

            $('body').on('click', 'a.faculty-update', function (e) {
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
                                    $('.' + elementName).val(value);
                                });
                            }
                        }
                    }
                });
                $('#myModal').find(".modal-title").text('Sửa thông tin khoa');
                $('#myModal').find(".modal-footer > button[name=btn-save-faculty]").html('Sửa')
                $('#myModal').find(".modal-footer > button[name=btn-save-faculty]").attr('data-link', urlUpdate);
                $('#myModal').modal('show');
            });


            $('body').on('click', '#btn-save-faculty', function (e) {
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
                            $.notify({
                                title: "Thành công  ",
                                message: ":D",
                                icon: 'fa fa-check'
                            },{
                                type: "success"
                            });
                            $('div#myModal').modal('hide');
                            oTable.draw();
                        }
                    }
                });
            });

            $('body').on('click', 'a.faculty-destroy', function (e) {
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
                                        oTable.draw();
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

            $('#myModal').on('hidden.bs.modal', function (e) {
                $('#myModal').find("input[type=text],input[type=number],input[type=hidden], select").val('').prop('disabled', false).prop('readonly', false);
                $('.text-red').html('');
                $('span.messageErrors').remove();
                $('#myModal').find(".modal-title").text('Thêm mới khoa');
                $('#myModal').find(".modal-footer > button[name=btn-save-faculty]").html('Thêm');
                $('#myModal').find(".modal-footer > button[name=btn-save-faculty]").attr('data-link', "{{ route('faculty-store') }}");
            });
        });
    </script>
@endsection
