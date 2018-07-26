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
                <div class="tile">
                    <div class="tile-body">
                        <form class="row" role="form" id="search-form" method="post">
                            {!! csrf_field() !!}
                            <div class="form-group col-md-3">
                                <label for="semester_id" class="control-label">Học kì</label>
                                <select class="form-control semester_id" name="semester_id" id="semester_id">
                                    @foreach($semesters as $value)
                                        <option {{ ($value['id'] == $currentSemester->id )? "selected" : "" }} value="{{ $value['id'] }}">{{ $value['value']}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="faculty_id" class="control-label">Khoa</label>
                                <select class="form-control faculty_id" name="faculty_id" id="faculty_id">
                                    @foreach($faculties as $value)
                                        <option value="{{ $value['id'] }}">{{ $value['name']}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="class_id" class="control-label">Lớp</label>
                                <select class="form-control class_id" name="class_id" id="class_id">
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
                        {{-- <div style="overflow:auto"> --}}
                        <table class="table table-hover table-bordered" id="studentsTranscript">
                            <thead>
                            <tr>
                                <th>MSSV</th>
                                <th>Tên</th>
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
                            </tfoot>
                        </table>
                        {{-- </div> --}}
                    </div>
                    <div class="row">
                        <div class="col-md-10">
                            <button class="btn btn-outline-success" id="createFile">
                                <i class="fa fa-download" aria-hidden="true"></i>Lập bảng tổng hợp đánh giá chưa áp dụng danh sách kỷ luật
                            </button>

                            {{-- quyền cho phép import. chỉ CTSV mới đc impỏt ds này--}}
                            @can('import-student-list-each-semester')
                            <button data-toggle="modal" data-target="#importModal" class="btn btn-outline-primary"
                                             type="button"><i class="fa fa-pencil-square-o"
                                                              aria-hidden="true"></i> Nhập danh sách sinh viên mới đánh giá mới
                            </button>
                            @endcan

                        </div>
                        <div class="col-md-2">
                               <span class="leds-test">
                                <button class="btn btn-info btn-show-notes" title="
Lưu ý: Khi xuất file chỉ xuất những giá trị hiện đang hiển thị.
Muốn xuất tất cả giá trị. Chọn 'Tất cả' ở số lượng hiển thị mỗi trang"><i style="background:#ff0000;color:#ff0000;"
                                                                          class="led led-sm on blink"></i></button>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <form id="export-students" action="{{route('export-users')}}" method="post">
            {!! csrf_field() !!}
            <input type="hidden" name="strUsersId" id="strUsersId">
            <input type="hidden" name="strUserName" id="strUserName">
            <input type="hidden" name="strClassName" id="strClassName">
            <input type="hidden" name="semesterChoose" id="semesterChoose" value="{{$currentSemester->id}}">
            <input type="hidden" name="facultyChoose" id="facultyChoose">
        </form>
        @can('import-student-list-each-semester')
            <div class="modal fade" id="importModal" role="dialog">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Chọn file excel muốn nhập danh sách</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span
                                    aria-hidden="true">×</span></button>
                    </div>
                    <div class="modal-body">
                        <form id="import-student-form">
                            {!! csrf_field() !!}
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-row">
                                        <label for="fileImport">Chọn file</label>
                                        <input type="file" multiple class="form-control fileImport" name="fileImport"
                                               id="fileImport">
                                    </div>
                                </div>
                            </div>
                        </form>
                        <br>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="alert alert-danger show-error bs-component" style="display: none">
                                    <ul class="list-group">
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button data-link="{{ route('import-student-list-each-semester') }}" class="btn btn-primary"
                                id="btn-import-student" name="btn-import-student" type="button">
                            Thêm
                        </button>
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Đóng</button>
                    </div>
                </div>
            </div>
        </div>
        @endcan
    </main>

@endsection

@section('sub-javascript')
    <script>
        var oTable = $('#studentsTranscript').DataTable({
            "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Tất cả"]],
            "autoWidth": false,
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
                // {data: "display_name", name: "roles.display_name"},
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
                "zeroRecords": "Không có bản ghi nào!",
                "info": "Hiển thị trang _PAGE_ của _PAGES_",
                "infoEmpty": "Không có bản ghi nào!!!",
                "infoFiltered": "(Đã lọc từ tổng _MAX_ bản ghi)",
                processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Tải dữ liệu...</span>'
            },
            "pageLength": 10,
        });

        $('#search-form').on('submit', function (e) {
            oTable.draw();
            e.preventDefault();
        });


        {{--$('select.semester_id').change(function () {--}}
            {{--var semesterId = $(this).val();--}}
            {{--var url = "{{ route('class-get-list-by-semester-and-userlogin-none') }}";--}}
            {{--$.ajax({--}}
                {{--type: "post",--}}
                {{--url: url,--}}
                {{--data: {id: semesterId},--}}
                {{--dataType: 'json',--}}
                {{--success: function (data) {--}}
                    {{--$("select.class_id").empty();--}}
                    {{--$.each(data.classes, function (key, value) {--}}
                        {{--$("select.class_id").append('<option value="' + value.id + '">' + value.name + '</option>');--}}
                    {{--});--}}
                {{--}--}}
            {{--});--}}
        {{--});--}}

        // $('#studentsTranscript tbody').on( 'click', 'tr', function () {
        //     console.log( oTable.row( this ).data() );
        // });

        //lúc đầu chỉ là lấy lớp theo khoa. giờ bổ sung thêm lấy id của khoa cho thẻ input ẩn để export
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

        getClass();
        //lúc đầu chỉ là lấy lớp theo khoa. giờ bổ sung thêm lấy id của khoa cho thẻ input ẩn để export
        //khi load trang sẽ gán giá trị của khoa vào
        function getClass() {
            var facultyId = $('select.faculty_id').val();
            $("form#export-students").find("input#facultyChoose").val(facultyId);
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
        }
    </script>
    <script src="{{ asset('js/web/transcript/index.js') }}"></script>
@endsection
