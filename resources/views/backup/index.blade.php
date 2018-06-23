@extends('layouts.default')

@section('title')
    STU| Xuất File
@endsection
@section('css')
    {{--<link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">--}}
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.2.4/css/buttons.dataTables.min.css">
    {{--<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/b-1.2.4/jq-2.2.4/jszip-2.5.0/pdfmake-0.1.18/dt-1.10.13/b-colvis-1.2.4/b-flash-1.2.4/b-html5-1.2.4/cr-1.3.2/fh-3.1.2/r-2.1.0/sc-1.4.2/se-1.2.0/datatables.min.css"/>--}}
@endsection
@section('content')
    <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class="fa fa-file-text-o"></i> Backup dữ liệu</h1>
                <p>Trường Đại học Công nghệ Sài Gòn</p>
            </div>
            <ul class="app-breadcrumb breadcrumb side">
                <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
                <li class="breadcrumb-item">Trang chủ</li>
                <li class="breadcrumb-item active"><a href="{{route('export-file-list')}}"> Xuất file </a></li>
            </ul>
        </div>
        <div class="row">

            <div class="col-md-12 custom-quanly-taikhoan">
                <div class="tile">
                    <div class="col-md-12 ">
                        <div class="tile-title">
                            <h5>Lưu ý: Khi xuất file chỉ xuất những sinh viên hiện đang hiển thị </h5>
                            <h5>Muốn xuất tất cả sinh viên. Chọn "Tất cả" ở số lượng hiển thị mỗi trang</h5>
                        </div>
                        <div class="bs-component">
                            <ul class="nav nav-tabs">
                                <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#user">Tài
                                        khoản</a></li>
                                <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#eachSemester">Danh
                                        sách sinh viên đánh giá mỗi học kì</a></li>
                            </ul>
                            <div class="tab-content" id="myTabContent">
                                <div class="tab-pane fade active show" id="user">
                                    <div class="tile-body">
                                        <form class="row" role="form" id="search-backup-user-form" method="post">
                                            {!! csrf_field() !!}
                                            <div class="form-group col-md-2">
                                                <label class="control-label"></label>
                                                <select class="form-control search_role_id" name="search_role_id"
                                                        id="search_role_id">
                                                    @foreach($rolesForSelectSearch as $value)
                                                        <option value="{{ $value['id'] }}">{{ $value['display_name']}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group col-md-3 align-self-end">
                                                <button class="btn btn-primary" type="submit"><i
                                                            class="fa fa-fw fa-lg fa-search"></i>Tìm kiếm
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="tile-body">
                                        <table class="table table-hover table-bordered" id="userBackupTable">
                                            <thead>
                                            <tr>
                                                <th>Id</th>
                                                <th>MSSV</th>
                                                <th>Tên</th>
                                                <th>Email</th>
                                                <th>Địa chỉ</th>
                                                <th>Giới tính</th>
                                                <th>SĐT</th>
                                                <th>Ngày sinh</th>
                                                <th>Khoa</th>
                                                <th>Lớp</th>
                                                <th>Vai trò</th>
                                            </tr>
                                            </thead>
                                            <tfoot>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="eachSemester">
                                    <div class="tile-body">
                                        <form class="row" role="form" id="search-each-semester-form" method="post">
                                            {!! csrf_field() !!}
                                            <div class="form-group col-md-3">
                                                <label class="control-label"></label>
                                                <select class="form-control semester_id" name="semester_id"
                                                        id="semester_id">
                                                    @foreach($semesters as $value)
                                                        <option {{ ($value['id'] == $currentSemester->id )? "selected" : "" }} value="{{ $value['id'] }}">{{ $value['value']}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group col-md-3">
                                                <label class="control-label"></label>
                                                <select class="form-control faculty_id" name="faculty_id"
                                                        id="faculty_id">
                                                    @foreach($faculties as $value)
                                                        <option value="{{ $value['id'] }}">{{ $value['name']}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group col-md-3 align-self-end">
                                                <button class="btn btn-primary" type="submit"><i
                                                            class="fa fa-fw fa-lg fa-search"></i>Tìm kiếm
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="tile-body">
                                        <table class="table table-hover table-bordered" id="eachSemesterBackupTable">
                                            <thead>
                                            <tr>
                                                <th>MSSV</th>
                                                <th>Tên</th>
                                                <th>Lớp</th>
                                                <th>Khoa</th>
                                                <th>Khóa</th>
                                                <th>BCSL</th>
                                                <th>CVHT</th>
                                                <th>Học kì</th>
                                            </tr>
                                            </thead>
                                            <tfoot>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

@endsection

@section('sub-javascript')
    {{--<script src="https://cdn.datatables.net/buttons/1.2.4/js/dataTables.buttons.min.js"></script>--}}
    {{--    <script src="{{ asset('vendor/datatables/buttons.server-side.js')  }}"></script>--}}
    <script src="https://cdn.datatables.net/v/dt/jq-2.2.4/jszip-2.5.0/pdfmake-0.1.18/dt-1.10.13/b-1.2.4/b-colvis-1.2.4/b-flash-1.2.4/b-html5-1.2.4/cr-1.3.2/fh-3.1.2/r-2.1.0/sc-1.4.2/se-1.2.0/datatables.min.js"></script>
    {{--    <script src="{{ asset('js/datatables-export.min.js') }}"></script>--}}
    {{--<script src="https://cdn.datatables.net/v/dt/jq-2.2.4/jszip-2.5.0/pdfmake-0.1.18/dt-1.10.13/b-colvis-1.2.4/b-flash-1.0.3/b-html5-1.2.4/cr-1.3.2/datatables.min.js"></script>--}}

    <script type="text/javascript">
        $('body').on('click', '.nav-item', function (e) {
            var tabs = $(this).children().attr('href');
            // $("div.tab-pane").removeClass('active');
            $("div.tab-pane").fadeOut(200);
            $("div" + tabs).fadeIn(200);
        });

        var userBackupTable = $('#userBackupTable').DataTable({
            "lengthMenu": [[10, 25, 50, 100, 150, -1], [10, 25, 50, 100, 150, 'Tất cả']],
            "dom": '<"top"Bifpl<"clear">>rt<"bottom"ip<"clear">>',
            buttons: [
                'copy', 'excel', 'pdf'
            ],
            "processing": true,
            "serverSide": true,
            "autoWidth": false,
            "ajax": {
                "url": "{{ route('ajax-get-backup-users') }}",
                "dataType": "json",
                "type": "POST",
                "data": function (d) {
                    d.role_id = $('select[name=search_role_id]').val();
                    d._token = "{{ csrf_token() }}";
                },
            },
            "columns": [
                {data: "id", name: "users.id"},
                {data: "users_id", name: "users.users_id"},
                {data: "name", name: "users.name"},
                {data: "email", name: "users.email"},
                {data: "gender", name: "users.gender"},
                {data: "address", name: "users.address"},
                {data: "phone_number", name: "users.phone_number"},
                {data: "birthday", name: "users.birthday"},
                {data: "facultyName", name: "faculties.name"},
                {data: "className", name: "classes.name"},
                {data: "roleName", name: "roles.display_name"}
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
            "pageLength": 10,
        });
        $("div#userBackupTable_paginate").css('display', 'none');
        $('#search-backup-user-form').on('submit', function (e) {
            userBackupTable.draw();
            e.preventDefault();
        });





        var eachSemesterBackupTable = $('#eachSemesterBackupTable').DataTable({
            "lengthMenu": [[10, 25, 50, 100, 150, -1], [10, 25, 50, 100, 150, 'Tất cả']],
            "dom": '<"top"Bifpl<"clear">>rt<"bottom"ip<"clear">>',
            buttons: [
                'copy', 'excel', 'pdf'
            ],
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "{{ route('ajax-get-backup-each-semester') }}",
                "dataType": "json",
                "type": "POST",
                "data": function (d) {
                    d.faculty_id = $('select[name=faculty_id]').val();
                    d.semester_id = $('select[name=semester_id]').val();
                    d._token = "{{ csrf_token() }}";
                },
            },
            "columns": [
                {data: "users_id", name: "users.users_id"},
                {data: "userName", name: "users.name"},
                {data: "className", name: "classes.name"},
                {data: "facultyName", name: "faculties.name"},
                {data: "academic", name: "students.academic_year_from"},
                {data: "monitor", name: "student_list_each_semesters.monitor_id"},
                {data: "staffName", name: "student_list_each_semesters.staff_id"},
                {data: "semesterInfo", name: "student_list_each_semesters.semester_id",orderable: false, searchable: false},
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
            "pageLength": 10,
        });

        $('#search-each-semester-form').on('submit', function (e) {
            eachSemesterBackupTable.draw();
            e.preventDefault();
        });

    </script>
@endsection