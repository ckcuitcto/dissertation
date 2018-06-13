@extends('layouts.default')
@section('content')
    <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class="fa fa-file-text-o"></i> Danh sách sinh viên</h1>
                <p>Trường Đại học Công nghệ Sài Gòn</p>
            </div>
            <ul class="app-breadcrumb breadcrumb side">
                <li class="breadcrumb-item"><a href="{{route('home')}}"><i class="fa fa-home fa-lg"></i></a></li>
                <li class="breadcrumb-item active"> Danh sách Sinh viên</li>
            </ul>
        </div>
        <div class="row">
            <div class="col-md-12 custom-quanly-taikhoan">
                {{--<div class="tile">--}}

                {{--</div>--}}
                <div class="tile">
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
                            <div class="form-group col-md-3">
                                <label class="control-label">Lớp</label>
                                <select class="form-control class_id" name="class_id" id="class_id">
                                </select>
                            </div>
                            <div class="form-group col-md-4 align-self-end">
                                <button class="btn btn-primary" type="submit"><i class="fa fa-fw fa-lg fa-search"></i>Tìm kiếm</button>
                            </div>
                        </form>
                    </div>
                    <div class="tile-body">
                        <table class="table table-hover table-bordered" id="students">
                            <thead>
                            <tr>
                                <th>MSSV</th>
                                <th>Tên</th>
                                <th>Chức vụ</th>
                                <th>Lớp</th>
                                <th>Khoa</th>
                                <th>Khóa</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tfoot>
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
            var oTable = $('#students').DataTable({
                "processing": true,
                "serverSide": true,
                "ajax": {
                    "url": "{{ route('ajax-transcript-get-users') }}",
                    "dataType": "json",
                    "type": "POST",
                    "data": function (d) {
                        d.faculty_id = $('select[name=faculty_id]').val();
                        d.class_id = $('select[name=class_id]').val();
                        d.semester_id = $('select[name=semester_id]').val();
                        d._token = "{{ csrf_token() }}";
                    },
                },
                "columns": [
                    {data: "users_id", name: "users.users_id"},
                    {data: "userName", name: "users.name"},
                    {data: "display_name", name: "roles.display_name"},
                    {data: "className", name: "classes.name"},
                    {data: "facultyName", name: "faculties.name"},
                    {data: "academic", name: "students.academic_year_from"},
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

            $('select.faculty_id').change(function () {
                var facultyId = $(this).val();
                var url = "{{ route('class-get-list-by-faculty-none') }}";
                $.ajax({
                    type: "post",
                    url: url,
                    data: {id: facultyId},
                    dataType: 'json',
                    success: function (data) {
                        $("select.class_id").empty();
                        $.each(data.classes, function (key, value) {
                            $("select.class_id").append('<option value="' + value.id + '">' + value.name + '</option>');
                        });
                    }
                });
            });
        });
    </script>
@endsection
<style>
    .custom-quanly-taikhoan table.dataTable{
        width: 100% !important;
    }
</style>
