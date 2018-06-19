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
                            <div class="form-group col-md-3 align-self-end">
                                <button class="btn btn-primary" type="submit"><i class="fa fa-fw fa-lg fa-search"></i>Tìm kiếm</button>
                            </div>
                        </form>
                    </div>
                    <div class="tile-body">
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
                        </div>
                    <div class="row">
                        <div class="col-md-6">
                            <button class="btn btn-outline-success" id="createFile">
                                <i class="fa fa-download" aria-hidden="true"></i>Lập bảng tổng hợp đánh giá
                            </button>
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
    </main>

@endsection

@section('sub-javascript')
    <script type="text/javascript" src="{{ asset('template/js/plugins/bootstrap-notify.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('template/js/plugins/sweetalert.min.js') }}"></script>
    {{--<script type="text/javascript">$('#sampleTable').DataTable();</script>--}}
    <script>
        $(document).ready(function () {
            var oTable = $('#studentsTranscript').DataTable({
                // dom: "<'row'<'col-xs-12'<'col-xs-6'l>>r>"+
                // "<'row'<'col-xs-12't>>"+
                // "<'row'<'col-xs-12'<'col-xs-6'i><'col-xs-6'p>>>",
                "lengthMenu": [[10, 25, 50,100, -1], [10, 25, 50,100, "Tất cả"]],
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


            // khi tìm kiếm. sẽ lưu lại giá trị học kì và khoa lại
            $('body').on('submit', "form#search-form", function (e) {
                var form = $("form#search-form");
                $("form#export-students").find("input#semesterChoose").val(form.find("#semester_id").val());
                $("form#export-students").find("input#facultyChoose").val(form.find("#faculty_id").val());
                // $("form#export-students").find("input#facultyChoose").val(facultyId);
            });
            $("button#createFile").on('click',function () {

                var strUsersId = new Array();
                var strUserName = new Array();
                var strClassName = new Array();
                oTable.rows().every( function () {
                    var userId = this.data().users_id;
                    var userName = this.data().userName;
                    var className = this.data().className;

                    strUsersId.push(userId);
                    strUserName.push(userName);
                    strClassName.push(className);

                    // d.counter++; // update data source for the row
                    // this.invalidate(); // invalidate the data DataTables has cached for this row
                });
                if(strUsersId.length === 0 || strUserName.length === 0 || strClassName.length === 0){
                    $.notify({
                        title: "Lưu ý: ",
                        message: "Danh sách rỗng",
                        icon: 'fa fa-exclamation-triangle'
                    },{
                        type: "danger"
                    });
                }else{
                    // $('#ajax_loader').show();

                    $("form#export-students").find("input#strUsersId").val(strUsersId);
                    $("form#export-students").find("input#strUserName").val(strUserName);
                    $("form#export-students").find("input#strClassName").val(strClassName);
                    $("form#export-students").submit();
                }
            });

            // $('#studentsTranscript tbody').on( 'click', 'tr', function () {
            //     console.log( oTable.row( this ).data() );
            // });

            //lúc đầu chỉ là lấy lớp theo khoa. giờ bổ sung thêm lấy id của khoa cho thẻ input ẩn để export
            $('select.faculty_id').change(function () {
                var facultyId = $(this).val();
                // $("form#export-students").find("input#facultyChoose").val(facultyId);
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
        });
    </script>
@endsection
