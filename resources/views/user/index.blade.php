@extends('layouts.default')
@section('content')
    <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class="fa fa-file-text-o"></i> Quản lí tài khoản </h1>
                <p>Trường Đại học Công nghệ Sài Gòn</p>
            </div>
            <ul class="app-breadcrumb breadcrumb side">
                <li class="breadcrumb-item"><a href="{{'home'}}"><i class="fa fa-home fa-lg"></i></a></li>
                <li class="breadcrumb-item active"> Danh sách tài khoản</li>
            </ul>
        </div>
        <div class="row">
            <div class="col-md-12 custom-quanly-taikhoan">
                <div class="tile">
                    <div class="tile-body">
                        <form class="row" role="form" id="search-form" method="post">
                            {!! csrf_field() !!}
                            <div class="form-group col-md-2">
                                <label class="control-label">Role</label>
                                <select class="form-control search_role_id" name="search_role_id" id="search_role_id">
                                    @foreach($rolesForSelectSearch as $value)
                                        <option value="{{ $value['id'] }}">{{ $value['display_name']}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-2">
                                <label class="control-label">Khoa</label>
                                <select class="form-control search_faculty_id" name="search_faculty_id"
                                        id="search_faculty_id">
                                    @foreach($facultiesForSelectSearch as $value)
                                        <option value="{{ $value['id'] }}">{{ $value['name']}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-2">
                                <label class="control-label">Lớp</label>
                                <select class="form-control search_class_id" name="search_class_id"
                                        id="search_class_id">
                                </select>
                            </div>
                            <div class="form-group col-md-3 align-self-end">
                                <button class="btn btn-primary" type="submit"><i class="fa fa-fw fa-lg fa-search"></i>Tìm
                                    kiếm
                                </button>
                            </div>
                        </form>
                    </div>
                    <div class="tile-body">
                        <table class="table table-hover table-bordered" id="tableManageUsers">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Tên</th>
                                <th>Chức vụ</th>
                                <th>Trạng thái</th>
                                <th>Tác vụ</th>
                            </tr>
                            </thead>
                            <tfoot>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            </tfoot>
                        </table>
                        <div class="row">
                            <div class="col-md-12">
                                <button data-toggle="modal" data-target="#modal-add-user" class="btn btn-primary"
                                        id="btn-add-user" type="button"><i class="fa fa-pencil-square-o"
                                                                           aria-hidden="true"></i>Thêm tài khoản
                                </button>
                                <button data-toggle="modal" data-target="#importModal" class="btn btn-outline-primary"
                                        type="button"><i class="fa fa-pencil-square-o"
                                                         aria-hidden="true"></i> Nhập danh sách sinh viên khóa mới.
                                </button>
                                <a href="{{ asset('upload/file_mau/File_mau.xlsx') }}" class="btn btn-outline-success">
                                    <i class="fa fa-download" aria-hidden="true"></i>Tải file mẫu nhập danh sách sinh
                                    viên khóa mới
                                </a>
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
                        <h5 class="modal-title"> Sửa thông tin</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span
                                    aria-hidden="true">×</span></button>
                    </div>
                    <div class="modal-body">
                        <form id="user-form" method="post" class="form-horizontal">
                            {{ csrf_field() }}
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <label class="control-label col-md-3">Mã</label>
                                        <div class="col-md-8">
                                            <input class="form-control users_id" type="text" name="users_id">
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
                                                    <input class="form-check-input gender" type="radio"
                                                           value="{{ FEMALE }}"
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
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span
                                    aria-hidden="true">×</span></button>
                    </div>
                    <div class="modal-body">
                        <form id="user-add-form" method="post" class="form-horizontal">
                            {{ csrf_field() }}
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <label class="control-label col-md-3">Mã</label>
                                        <div class="col-md-8">
                                            <input class="form-control users_id" type="text" name="users_id">
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
                                                    <input class="form-check-input gender" type="radio"
                                                           value="{{ FEMALE }}"
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
                            <button data-link="{{ route('user-store') }}" class="btn btn-primary" id="btn-add"
                                    name="btn-add" type="button">
                                Thêm
                            </button>
                            <button class="btn btn-secondary" type="button" data-dismiss="modal">Đóng</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="importModal" role="dialog">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Chọn file excel muốn nhập danh sách...</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span
                                    aria-hidden="true">×</span></button>
                    </div>
                    <div class="modal-body">
                        <form id="import-student-form">
                            {!! csrf_field() !!}
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-row">
                                        <label for="fileImport">Chọn file excel</label>
                                        <input type="file" multiple class="form-control fileImport" name="fileImport"
                                               id="fileImport">
                                    </div>
                                </div>
                            </div>
                        </form>
                        <br>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="alert alert-danger show-error" style="display: none">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button data-link="{{ route('student-import') }}" class="btn btn-primary"
                                id="btn-import-student" name="btn-import-student" type="button">
                            Thêm
                        </button>
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Đóng</button>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

@section('sub-javascript')
    <script>
        var oTable = $('#tableManageUsers').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "{{ route('ajax-user-get-users') }}",
                "dataType": "json",
                "type": "POST",
                "data": function (d) {
                    d.faculty_id = $('select[name=search_faculty_id]').val();
                    d.class_id = $('select[name=search_class_id]').val();
                    d.role_id = $('select[name=search_role_id]').val();
                    d._token = "{{ csrf_token() }}";
                },
            },
            "columns": [
                {data: "users_id", name: "users.users_id"},
                {data: "userName", name: "users.name"},
                {data: "display_name", name: "roles.display_name"},
                {data: "status", name: "users.status"},
                {data: 'action', name: 'action', orderable: false, searchable: false}
            ],
            initComplete: function () {
                this.api().columns().every(function () {
                    var column = this;
                    var input = document.createElement("input");
                    $(input).appendTo($(column.footer()).empty())
                        .on('change', function () {
                            var val = $.fn.dataTable.util.escapeRegex($(this).val());
                            column.search(val ? val : '', true, false).draw();
                        });
                });
            },
            "language": {
                "lengthMenu": "Hiển thị _MENU_ bản ghi mỗi trang",
                "zeroRecords": "Không có bản ghi nào!",
                "info": "Hiển thị trang _PAGE_ của _PAGES_",
                "infoEmpty": "Không có bản ghi nào!!!",
                "infoFiltered": "(Đã lọc từ _MAX_ total bản ghi)",
                processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Tải dữ liệu...</span>'
            },
            "pageLength": 25
        });

        $('#search-form').on('submit', function (e) {
            oTable.draw();
            e.preventDefault();
        });

        $('body').on('change', 'select.search_faculty_id', function (e) {
            // $('select.search_faculty_id').change(function () {
            var facultyId = $(this).val();
            var url = "{{ route('class-get-list-by-faculty-none') }}";
            $.ajax({
                type: "post",
                url: url,
                data: {id: facultyId},
                dataType: 'json',
                success: function (data) {
                    $("select.search_class_id").empty();
                    $.each(data.classes, function (key, value) {
                        $("select.search_class_id").append('<option value="' + value.id + '">' + value.name + '</option>');
                    });
                }
            });
        });


        // ẩn hiện khi chọn role
        // $('div#modal-add-user').on('change', 'select.role_id', function (e) {
        // $('select.role_id').change(function (e) {
        $('body').on('change', 'select.role_id', function (e) {

            var roleId = $(this).val();
            if (roleId === "{{ ROLE_PHONGCONGTACSINHVIEN }}" || roleId === "{{ ROLE_ADMIN }}") {
                $("div#faculty").hide();
                $("div#classes").hide();
                // $('div#modal-add-user').find("select.faculty_id").prop('disabled', true);
                // $('div#modal-add-user').find("select.classes_id").prop('disabled', true);
                $("select.faculty_id").prop('disabled', true);
                $("select.classes_id").prop('disabled', true);
            } else if (roleId === "{{ ROLE_BANCANSULOP }}" || roleId === "{{ ROLE_SINHVIEN }}") {
                $("div#faculty").show();
                $("div#classes").show();
                $("select.faculty_id").prop('disabled', false);
                $("select.classes_id").prop('disabled', false);
            } else {
                $("div#faculty").show();
                $("div#classes").hide();
                $("select.faculty_id").prop('disabled', false);
                $("select.classes_id").prop('disabled', true);
            }
        });

        // $('div#modal-add-user').on('change', 'select.faculty_id', function (e) {
        // $('select.faculty_id').change(function () {
        $('body').on('change', 'select.faculty_id', function (e) {

            var facultyId = $(this).val();
            var url = "{{ route('class-get-list-by-faculty') }}";
            $.ajax({
                type: "post",
                url: url,
                data: {id: facultyId},
                dataType: 'json',
                success: function (data) {
                    $("select.classes_id").empty();
                    $.each(data.classes, function (key, value) {
                        $("select.classes_id").append('<option value="' + value.id + '">' + value.name + '</option>');
                    });
                }
            });
        });

        $('#modal-edit-user').on('hidden.bs.modal', function (e) {
            // hideAndShowFaculty('modal-edit-user');
            $('#modal-edit-user').find("input[type=text],input[type=number],input[type=year],input[type=hidden], select").val('');
            $('.text-red').html('');
            $('span.messageErrors').remove();
        });

        getClassByFacultyId('modal-add-user');

        function getClassByFacultyId(modal) {
            var facultyId = $('div#' + modal).find('select.faculty_id').val();
            var url = "{{ route('class-get-list-by-faculty') }}";
            $.ajax({
                type: "post",
                url: url,
                data: {id: facultyId},
                dataType: 'json',
                success: function (data) {
                    $('div#' + modal).find("select.classes_id").empty();
                    $.each(data.classes, function (key, value) {
                        $('div#' + modal).find("select.classes_id").append('<option value="' + value.id + '">' + value.name + '</option>');
                    });
                }
            });
        }

        hideAndShowFaculty('modal-add-user');
        hideAndShowFaculty('modal-edit-user');

        function hideAndShowFaculty(modal) {
            var roleId = $('div#' + modal).find('select.role_id').val();
            if (roleId === "{{ ROLE_PHONGCONGTACSINHVIEN }}" || roleId === "{{ ROLE_ADMIN }}") {
                $('div#' + modal).find("div#faculty").hide();
                $('div#' + modal).find("div#classes").hide();
            } else if (roleId === "{{ ROLE_BANCHUNHIEMKHOA }}" || roleId === "{{ ROLE_COVANHOCTAP }}") {
                $('div#' + modal).find("div#faculty").show();
                $('div#' + modal).find("div#classes").hide();
            } else {
                $('div#' + modal).find("div#faculty").show();
                $('div#' + modal).find("div#classes").show();
            }
        }

    </script>
    <script src="{{ asset('js/web/user/index.js') }}"></script>
@endsection
