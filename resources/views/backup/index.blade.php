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
                <h1><i class="fa fa-file-text-o"></i> Xuất file</h1>
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
                        <div class="bs-component">
                            <ul class="nav nav-tabs">
                                <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#user">User</a></li>
                                <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#profile">Profile</a></li>
                            </ul>
                            <div class="tab-content" id="myTabContent">
                                <div class="tab-pane fade active show" id="user">
                                    {{--<div class="tile-body">--}}
                                        {{--<form class="row" role="form" id="search-form" method="post">--}}
                                            {{--{!! csrf_field() !!}--}}
                                            {{--<div class="form-group col-md-3">--}}
                                                {{--<label class="control-label">Học kì</label>--}}
                                                {{--<select class="form-control semester_id" name="semester_id" id="semester_id">--}}
                                                    {{--@foreach($semesters as $value)--}}
                                                        {{--<option {{ ($value['id'] == $currentSemester->id )? "selected" : "" }} value="{{ $value['id'] }}">{{ $value['value']}}</option>--}}
                                                    {{--@endforeach--}}
                                                {{--</select>--}}
                                            {{--</div>--}}
                                            {{--<div class="form-group col-md-3">--}}
                                                {{--<label class="control-label">Khoa</label>--}}
                                                {{--<select class="form-control faculty_id" name="faculty_id" id="faculty_id">--}}
                                                    {{--@foreach($faculties as $value)--}}
                                                        {{--<option value="{{ $value['id'] }}">{{ $value['name']}}</option>--}}
                                                    {{--@endforeach--}}
                                                {{--</select>--}}
                                            {{--</div>--}}
                                            {{--<div class="form-group col-md-3 align-self-end">--}}
                                                {{--<button class="btn btn-primary" type="submit"><i class="fa fa-fw fa-lg fa-search"></i>Tìm kiếm</button>--}}
                                            {{--</div>--}}
                                        {{--</form>--}}
                                    {{--</div>--}}
                                    <br>
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
                                <div class="tab-pane fade" id="profile">
                                    <div class="tile-body">
                                        <form class="row" role="form" id="search-form" method="post">
                                            {!! csrf_field() !!}
                                            <div class="form-group col-md-3">
                                                <label class="control-label">Học kì</label>
                                                <select class="form-control semester_id" name="semester_id" id="semester_id">
                                                    @foreach($semesters as $value)
                                                        <option {{ ($value['id'] == $currentSemester->id )? "selected" : "" }} value="{{ $value['id'] }}">{{ $value['value']}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group col-md-3">
                                                <label class="control-label">Khoa</label>
                                                <select class="form-control faculty_id" name="faculty_id" id="faculty_id">
                                                    @foreach($faculties as $value)
                                                        <option value="{{ $value['id'] }}">{{ $value['name']}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group col-md-3 align-self-end">
                                                <button class="btn btn-primary" type="submit"><i class="fa fa-fw fa-lg fa-search"></i>Tìm kiếm</button>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="tile-body">
                                        <form id="class-form-export" action="{{route('export-file')}}" method="post">
                                            {{ csrf_field() }}
                                            <table class="table table-hover table-bordered" id="exportTable">
                                                <thead>
                                                <tr>
                                                    <th>Lớp</th>
                                                    <th>Số lượng sinh viên</th>
                                                    <th class="nosort">
                                                        <div class="animated-checkbox">
                                                            <label>
                                                                <input type="checkbox" name="checkAll"><span class="label-text">Xuất file đánh giá điểm rèn luyện</span>
                                                            </label>
                                                        </div>
                                                    </th>
                                                </tr>
                                                </thead>
                                                <tfoot>
                                                <th></th>
                                                </tfoot>
                                            </table>
                                            <input type="hidden" name="semesterChoose" id="semesterChoose" value="{{$currentSemester->id}}">
                                        </form>
                                        <div class="row">
                                            <div class="col-md-6">

                                                <button class="btn btn-info" id="btnExport" type="button" data-link="{{route('export-file')}}">
                                                    <i class="fa fa-file-excel-o" aria-hidden="true"></i>
                                                    Xuất File
                                                </button>
                                            </div>
                                        </div>
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

            var oTable = $('#userBackupTable').DataTable({
                "lengthMenu": [[10, 25, 50, 100, 150, -1], [10,25, 50, 100, 150, 'Tất cả']],
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
                    "data": {
                        "_token": "{{ csrf_token() }}"
                    }
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
                "language": {
                    "lengthMenu": "Hiển thị _MENU_ bản ghi mỗi trang",
                    // "zeroRecords": "Không có bản ghi nào!",
                    // "info": "Hiển thị trang _PAGE_ của _PAGES_",
                    "infoEmpty": "Không có bản ghi nào!!!",
                    "infoFiltered": "(Đã lọc từ _MAX_ total bản ghi)"
                },
                "pageLength": 10,
            });
            $("div#userBackupTable_paginate").css('display','none');

            $('body').on('click', '.nav-item', function (e){
                var tabs = $(this).children().attr('href');
                // $("div.tab-pane").removeClass('active');
                $("div.tab-pane").fadeOut(200);
                $("div"+tabs).fadeIn(200);
            });

            var oTable = $('#exportTable').DataTable({
                "processing": true,
                "serverSide": true,
                "ajax": {
                    "url": "{{ route('ajax-get-class-export') }}",
                    "dataType": "json",
                    "type": "POST",
                    "data": function (d) {
                        d.faculty_id = $('select[name=faculty_id]').val();
                        d.semester_id = $('select[name=semester_id]').val();
                        d._token = "{{ csrf_token() }}";
                    },
                },
                "columns": [
                    {data: "name", name: "classes.name"},
                    {data: "count", name: "count",searchable: false},
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
                    // "zeroRecords": "Không có bản ghi nào!",
                    // "info": "Hiển thị trang _PAGE_ của _PAGES_",
                    "infoEmpty": "Không có bản ghi nào!!!",
                    "infoFiltered": "(Đã lọc từ _MAX_ total bản ghi)"
                },
                "pageLength": 10,
            });

            $('#search-form').on('submit', function(e) {
                oTable.draw();
                e.preventDefault();
            });

            $('body').on('change', "select#semester_id", function (e) {
                $("input#semesterChoose").val($(this).val());
            });

    </script>
@endsection