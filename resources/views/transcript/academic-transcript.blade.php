@extends('layouts.default')
@section('content')
    <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class="fa fa-file-text-o"></i> Điểm rèn luyện sinh viên</h1>
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
                                <th>I.a</th>
                                <th>I.b</th>
                                <th>I.c</th>
                                <th>I</th>
                                <th>II</th>
                                <th>III</th>
                                <th>IV</th>
                                <th>V</th>
                                <th>Tổng</th>
                                <th>Tác vụ</th>
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
                            <button class="btn btn-primary" id="addAcademicTranscript" data-toggle="modal" data-target="#myModal">
                                <i class="fa fa-download" aria-hidden="true"></i>Thêm mới điểm cho sinh viên
                            </button>

                            <button class="btn btn-outline-success" id="createFile">
                                <i class="fa fa-download" aria-hidden="true"></i>Lập bảng tổng hợp đánh giá đã áp dụng danh sách kỷ luật
                            </button>
                            <button class="btn btn-outline-success" id="createFileResultAllCourse">
                                <i class="fa fa-download" aria-hidden="true"></i>Lập bảng kết quả đánh giá rèn luyện sinh viên toàn khóa học
                            </button>

                        </div>
                        <div class="col-md-2">
                               <span class="leds-test">
                                <button class="btn btn-info btn-show-notes" data-toggle="tooltip" title="
Lưu ý: Khi xuất file chỉ xuất những giá trị hiện đang hiển thị.
Muốn xuất tất cả giá trị. Chọn 'Tất cả' ở số lượng hiển thị mỗi trang"><i style="background:#ff0000;color:#ff0000;"
                                                                          class="led led-sm on blink"></i></button>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <form id="export-academic-transcript" action="{{route('export-academic-transcript')}}" method="post">
            {!! csrf_field() !!}
            <input type="hidden" name="strUsersId" class="strUsersId">
            <input type="hidden" name="strUserName" class="strUserName">
            <input type="hidden" name="strClassName" class="strClassName">
            <input type="hidden" name="semesterChoose" class="semesterChoose" value="{{$currentSemester->id}}">
            <input type="hidden" name="facultyChoose" class="facultyChoose">
        </form>
        <form id="export-academic-transcript-all-course" action="{{route('export-academic-transcript-all-course')}}" method="post">
            {!! csrf_field() !!}
            <input type="hidden" name="strUsersId" class="strUsersId">
            <input type="hidden" name="strUserName" class="strUserName">
            <input type="hidden" name="strClassName" class="strClassName">
            <input type="hidden" name="semesterChoose" class="semesterChoose" value="{{$currentSemester->id}}">
            <input type="hidden" name="facultyChoose" class="facultyChoose">
        </form>
        <div class="modal fade" id="myModal" role="dialog">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Thêm mới điểm cho sinh viên</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span
                                    aria-hidden="true">×</span></button>
                    </div>
                    <div class="modal-body">
                        <form id="academic-transcript-form">
                            {!! csrf_field() !!}
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-row">
                                        <label for="add_faculty_id">Khoa</label>
                                        <div class="input-group">
                                            <select class="form-control add_faculty_id" name="add_faculty_id" id="add_faculty_id">
                                                @foreach($facultiesNoAll as $value)
                                                    <option value="{{ $value['id'] }}">{{ $value['name']}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <label for="add_class_id" class="control-label">Lớp</label>
                                        <div class="input-group">
                                            <select class="form-control add_class_id" name="add_class_id" id="add_class_id">
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <label for="add_student_id" class="control-label">Sinh viên</label>
                                        <div class="input-group">
                                            <select class="form-control add_student_id" name="add_student_id" id="add_student_id">
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-row">
                                        <label for="add_semester_id" class="control-label">Học kì</label>
                                        <select class="form-control add_semester_id" name="add_semester_id" id="add_semester_id">
                                            @foreach($semestersNoAll as $value)
                                                <option {{ ($value['id'] == $currentSemester->id )? "selected" : "" }} value="{{ $value['id'] }}">{{ $value['value']}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-row">
                                        <label for="note" class="control-label">Ghi chú</label>
                                        <textarea class="form-control note" name="note" id="note" cols="3" rows="4"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                @foreach($evaluationCriterias as $value)
                                    <div class="{{ ($value->level == 1) ? "col-md-12" : "col-md-12" }}">
                                        <div class="form-row">
                                            <label for="{{ "evaluation_criteria_".$value->id }}" class="control-label">{{ $value->content }}</label>
                                            <div class="input-group">
                                                <input type="number" class="form-control {{ "evaluation_criteria_".$value->id }}" id="{{ "evaluation_criteria_".$value->id }}" name="{{ "evaluation_criteria_".$value->id }}">
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </form>
                        <div class="modal-footer">
                            <button data-link="{{ route('add-academic-transcript') }}" class="btn btn-primary"
                                    id="academic-transcript-store" name="academic-transcript-store" type="button">
                                Thêm
                            </button>
                            <button class="btn btn-secondary" id="closeForm" type="button" data-dismiss="modal">Đóng
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

@endsection

@section('sub-javascript')
    <script>
        $('body').on('click', '#academic-transcript-store', function (e) {
            var valueForm = $('form#academic-transcript-form').serialize();
            var url = $(this).attr('data-link');
            $('.form-row').find('span.messageErrors').remove();
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
                                    $('form#academic-transcript-form').find('.' + elementName).parents('.form-row').append('<span class="messageErrors" style="color:red">' + messageValue + '</span>');
                                });
                            });
                        }
                    } else if (result.status === true) {
                        $.notify({
                            title: "Thành công",
                            message: ":D",
                            icon: 'fa fa-check'
                        }, {
                            type: "success"
                        });
                        $('div#myModal').modal('hide');
                        oTable.draw();
                    }
                }
            });
        });


        $('[data-toggle="tooltip"]').tooltip();
        var oTable = $('#studentsTranscript').DataTable({
            "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Tất cả"]],
            "autoWidth": false,
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "{{ route('ajax-academic-transcript') }}",
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
                {data: "score_ia", name: "academic_transcripts.score_ia"},
                {data: "score_ib", name: "academic_transcripts.score_ib"},
                {data: "score_ic", name: "academic_transcripts.score_ic"},
                {data: "score_i", name: "academic_transcripts.score_i"},
                {data: "score_ii", name: "academic_transcripts.score_ii"},
                {data: "score_iii", name: "academic_transcripts.score_iii"},
                {data: "score_iv", name: "academic_transcripts.score_iv"},
                {data: "score_v", name: "academic_transcripts.score_v"},
                {data: "totalScore", name: "totalScore", searchable: false},
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

        {{--//lúc đầu chỉ là lấy lớp theo khoa. giờ bổ sung thêm lấy id của khoa cho thẻ input ẩn để export--}}
        {{--$('select.faculty_id').change(function () {--}}
            {{--var facultyId = $(this).val();--}}
            {{--var url = "{{ route('class-get-list-by-faculty-none') }}";--}}
            {{--$.ajax({--}}
                {{--type: "post",--}}
                {{--url: url,--}}
                {{--data: {id: facultyId},--}}
                {{--dataType: 'json',--}}
                {{--success: function (data) {--}}
                    {{--$("select.class_id").empty();--}}
                    {{--$.each(data.classes, function (key, value) {--}}
                        {{--$("select.class_id").append('<option value="' + value.id + '">' + value.name + '</option>');--}}
                    {{--});--}}
                {{--}--}}
            {{--});--}}
        {{--});--}}

        {{--getClass();--}}
        {{--//lúc đầu chỉ là lấy lớp theo khoa. giờ bổ sung thêm lấy id của khoa cho thẻ input ẩn để export--}}
        {{--//khi load trang sẽ gán giá trị của khoa vào--}}
        {{--function getClass() {--}}
            {{--var facultyId = $('select.faculty_id').val();--}}
            {{--$("form#export-students").find("input#facultyChoose").val(facultyId);--}}
            {{--var url = "{{ route('class-get-list-by-faculty-none') }}";--}}
            {{--$.ajax({--}}
                {{--type: "post",--}}
                {{--url: url,--}}
                {{--data: {id: facultyId},--}}
                {{--dataType: 'json',--}}
                {{--success: function (data) {--}}
                    {{--$("select.class_id").empty();--}}
                    {{--$.each(data.classes, function (key, value) {--}}
                        {{--$("select.class_id").append('<option value="' + value.id + '">' + value.name + '</option>');--}}
                    {{--});--}}
                {{--}--}}
            {{--});--}}
        {{--}--}}

        {{--$('select.faculty_id').change(function () {--}}
            {{--var facultyId = $(this).val();--}}
            {{--var url = "{{ route('class-get-list-by-faculty-none') }}";--}}
            {{--$.ajax({--}}
                {{--type: "post",--}}
                {{--url: url,--}}
                {{--data: {id: facultyId},--}}
                {{--dataType: 'json',--}}
                {{--success: function (data) {--}}
                    {{--$("select.class_id").empty();--}}
                    {{--$.each(data.classes, function (key, value) {--}}
                        {{--$("select.class_id").append('<option value="' + value.id + '">' + value.name + '</option>');--}}
                    {{--});--}}
                {{--}--}}
            {{--});--}}
        {{--});--}}
        @if($userLogin->Role->weight >= ROLE_PHONGCONGTACSINHVIEN)
            $('select.semester_id').change(function () {
                getClass();
            });
            //lúc đầu chỉ là lấy lớp theo khoa. giờ bổ sung thêm lấy id của khoa cho thẻ input ẩn để export
            $('select.faculty_id').change(function () {
                getClass();
            });

            {{--getClass();--}}
            {{--//lúc đầu chỉ là lấy lớp theo khoa. giờ bổ sung thêm lấy id của khoa cho thẻ input ẩn để export--}}
            {{--//khi load trang sẽ gán giá trị của khoa vào--}}
            function getClass() {
                var semesterId = $("#semester_id").val();
                var facultyId = $("#faculty_id").val();
                var url = "{{ route('class-get-list-by-semester-and-userlogin-none') }}";
                $.ajax({
                    type: "post",
                    url: url,
                    data: {id: semesterId,facultyId: facultyId},
                    dataType: 'json',
                    success: function (data) {
                        $("select.class_id").empty();
                        $.each(data.classes, function (key, value) {
                            $("select.class_id").append('<option value="' + value.id + '">' + value.name + '</option>');
                        });
                    }
                });
            }
            $('select.semester_id').trigger('change');
        @else
            $('select.semester_id').change(function () {
                var semesterId = $(this).val();
                var url = "{{ route('class-get-list-by-semester-and-userlogin-none') }}";
                $.ajax({
                    type: "post",
                    url: url,
                    data: {id: semesterId},
                    dataType: 'json',
                    success: function (data) {
                        $("select.class_id").empty();
                        $.each(data.classes, function (key, value) {
                            $("select.class_id").append('<option value="' + value.id + '">' + value.name + '</option>');
                        });
                    }
                });
            });
            $('select.semester_id').trigger('change');
        @endif

        $('body').on('submit', "form#search-form", function (e) {
            var form = $("form#search-form");
            $("form#export-academic-transcript").find("input#semesterChoose").val(form.find("#semester_id").val());
            $("form#export-academic-transcript").find("input#facultyChoose").val(form.find("#faculty_id").val());
            // $("form#export-academic-transcript").find("input#facultyChoose").val(facultyId);
        });

        // $('body').on('click', "button#createFile", function (e) {
        //
        //     var strUsersId = new Array();
        //     var strUserName = new Array();
        //     var strClassName = new Array();
        //     oTable.rows().every( function () {
        //         var userId = this.data().users_id;
        //         var userName = this.data().userName;
        //         var className = this.data().className;
        //
        //         strUsersId.push(userId);
        //         strUserName.push(userName);
        //         strClassName.push(className);
        //     });
        //     if(strUsersId.length === 0 || strUserName.length === 0 || strClassName.length === 0){
        //         $.notify({
        //             title: "Lưu ý: ",
        //             message: "Danh sách rỗng",
        //             icon: 'fa fa-exclamation-triangle'
        //         },{
        //             type: "danger"
        //         });
        //     }else{
        //         $("form#export-academic-transcript").find("input#strUsersId").val(strUsersId);
        //         $("form#export-academic-transcript").find("input#strUserName").val(strUserName);
        //         $("form#export-academic-transcript").find("input#strClassName").val(strClassName);
        //
        //         var dataForm = $("form#export-academic-transcript").serialize();
        //         var url = $("form#export-academic-transcript").attr('action');
        //         $.ajax({
        //             url: url,
        //             type: 'POST',
        //             cache: false,
        //             data: dataForm,
        //             beforeSend: function(){
        //                 $("#ajax_loader").show();
        //             },
        //             success: function (data) {
        //                 $("#ajax_loader").hide();
        //                 if (data.status === true) {
        //                     if(data.file_path !== undefined){
        //                         var a = document.createElement('a');
        //                         a.href = data.file_path;
        //                         a.download = data.file_name;
        //                         a.click();
        //                     }
        //                 } else {
        //                     swal("Không thành công", data.message +" !!! :)", "error");
        //                 }
        //             }
        //         });
        //     }
        // });
        //
        //
        // $('body').on('click', "button#createFileResultAllCourse", function (e) {
        //
        //     var strUsersId = new Array();
        //     var strUserName = new Array();
        //     var strClassName = new Array();
        //     oTable.rows().every( function () {
        //         var userId = this.data().users_id;
        //         var userName = this.data().userName;
        //         var className = this.data().className;
        //
        //         strUsersId.push(userId);
        //         strUserName.push(userName);
        //         strClassName.push(className);
        //     });
        //     if(strUsersId.length === 0 || strUserName.length === 0 || strClassName.length === 0){
        //         $.notify({
        //             title: "Lưu ý: ",
        //             message: "Danh sách rỗng",
        //             icon: 'fa fa-exclamation-triangle'
        //         },{
        //             type: "danger"
        //         });
        //     }else{
        //         $("form#export-academic-transcript-all-course").find("input#strUsersId").val(strUsersId);
        //         $("form#export-academic-transcript-all-course").find("input#strUserName").val(strUserName);
        //         $("form#export-academic-transcript-all-course").find("input#strClassName").val(strClassName);
        //
        //         var dataForm = $("form#export-academic-transcript-all-course").serialize();
        //         var url = $("form#export-academic-transcript-all-course").attr('action');
        //         $.ajax({
        //             url: url,
        //             type: 'POST',
        //             cache: false,
        //             data: dataForm,
        //             beforeSend: function(){
        //                 $("#ajax_loader").show();
        //             },
        //             success: function (data) {
        //                 $("#ajax_loader").hide();
        //                 if (data.status === true) {
        //                     if(data.file_path !== undefined){
        //                         var a = document.createElement('a');
        //                         a.href = data.file_path;
        //                         a.download = data.file_name;
        //                         a.click();
        //                     }
        //                 } else {
        //                     swal("Không thành công", data.message +" !!! :)", "error");
        //                 }
        //             }
        //         });
        //     }
        // });
        // phần này dùng cho select ở form input

        $('body').on('change', "select.add_faculty_id", function (e) {
            var facultyId = $(this).val();
            var url = "{{ route('get-classes-by-faculty') }}";
            $.ajax({
                type: "post",
                url: url,
                data: {id: facultyId},
                dataType: 'json',
                success: function (data) {
                    $("select#add_class_id").empty();
                    $.each(data.classes, function (key, value) {
                        $("select#add_class_id").append('<option value="' + value.id + '">' + value.name + '</option>');
                    });
                    $("select#add_class_id").trigger('change');
                }
            });
        });

        $('body').on('change', "select.add_class_id", function (e) {
            var classId = $(this).val();
            var url = "{{ route('get-students-by-class') }}";
            $.ajax({
                type: "post",
                url: url,
                data: {id: classId},
                dataType: 'json',
                success: function (data) {
                    $("select#add_student_id").empty();
                    $.each(data.students, function (key, value) {
                        $("select#add_student_id").append('<option value="' + value.users_id + '">'+ value.users_id + " - " + value.name + '</option>');
                    });
                }
            });
        });
        $("select.add_faculty_id").trigger('change');

        // $('body').on('click', 'a.update-academic-transcript', function (e) {
        //     var urlEdit = $(this).attr('data-edit-link');
        //     var urlUpdate = $(this).attr('data-update-link');
        //     var id = $(this).attr('data-semester-id');
        //     $('.form-row').find('span.messageErrors').remove();
        //     $.ajax({
        //         type: "get",
        //         url: urlEdit,
        //         data: {id: id},
        //         dataType: 'json',
        //         success: function (result) {
        //             if (result.status === true) {
        //                 if (result.academicTranscript !== undefined) {
        //                     var add_class_id;
        //                     var add_faculty_id;
        //                     var add_semester_id;
        //                     var add_student_id;
        //                     $.each(result.academicTranscript, function (elementName, value) {
        //                         if (elementName === 'add_class_id') {
        //                             $("select#add_class_id").empty().append('<option value="' + value + '">' + result.academicTranscript.add_class_name + '</option>').attr('readonly',true);
        //                         } else if (elementName === 'add_faculty_id') {
        //                             // $("select#add_faculty_id").empty().append('<option value="' + value + '">' + result.academicTranscript.add_faculty_name + '</option>').attr('readonly',true);
        //                             $("select#add_faculty_id").val(value).attr('disabled',true);
        //                         } else if (elementName === 'add_semester_id') {
        //                             $("#add_semester_id").val(value);
        //                         } else if (elementName === 'add_student_id') {
        //                             $("select#add_student_id").empty().append('<option value="' + value + '">' + result.academicTranscript.add_student_name + '</option>').attr('readonly',true);
        //                         } else if (elementName === 'note') {
        //                             $("textarea#"+elementName).html(value);
        //                         } else {
        //                             $('.' + elementName).val(value);
        //                         }
        //                     });
        //                 }
        //             }
        //         }
        //     });
        //     $('#myModal').find(".modal-title").text('Sửa điểm');
        //     $('#myModal').find(".modal-footer > button[name=academic-transcript-store]").html('Sửa');
        //     $('#myModal').find(".modal-footer > button[name=academic-transcript-store]").attr('data-link', urlUpdate);
        //     $('#myModal').modal('show');
        // });

        // $('#myModal').on('hidden.bs.modal', function (e) {
        //     $("select.add_faculty_id").trigger('change');
        //     $('#myModal').find("input[type=text],input[type=number], select").attr('readonly', false).attr('disabled', false);
        //     $('#myModal').find("input[type=text],input[type=number], textarea").val('');
        //     $('.text-red').html('');
        //     $('span.messageErrors').remove();
        //     $('#myModal').find(".modal-title").text('Thêm mới điểm cho sinh viên');
        //     $('#myModal').find(".modal-footer > button[name=academic-transcript-store]").html('Thêm');
        // });
    </script>
        <script src="{{ asset('js/web/transcript/academic-transcript.js') }}"></script>

@endsection
