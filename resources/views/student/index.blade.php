@extends('layouts.default')
@section('content')
    <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class="fa fa-file-text-o"></i> Danh sách sinh viên chấm điểm hiện tại</h1>
                <p>Trường Đại học Công nghệ Sài Gòn</p>
            </div>
            <ul class="app-breadcrumb breadcrumb side">
                <li class="breadcrumb-item"><a href="{{'home'}}"><i class="fa fa-home fa-lg"></i></a></li>
                <li class="breadcrumb-item active"> Danh sách Sinh viên</li>
            </ul>
        </div>
        <div class="row">
            <div class="col-md-12 custom-quanly-taikhoan">
                <div class="tile">
                    <div class="overlay custom-overlay" style="opacity: 0">
                        <div class="m-loader mr-4">
                            <svg class="m-circular" viewBox="25 25 50 50">
                                <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="4"
                                        stroke-miterlimit="10"/>
                            </svg>
                        </div>
                        <h3 class="l-text">Loading</h3>
                    </div>
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
                            <div class="form-group col-md-3 align-self-end">
                                <button class="btn btn-primary" type="submit"><i class="fa fa-fw fa-lg fa-search"></i>Tìm
                                    kiếm
                                </button>
                            </div>
                        </form>
                        <table class="table table-hover table-bordered" id="studentsEachList">
                            <thead>
                            <tr>
                                <th>MSSV</th>
                                <th>Tên</th>
                                <th>Chức vụ</th>
                                <th>Lớp</th>
                                <th>Khoa</th>
                                <th>Khóa</th>
                                <th>Điểm</th>
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
                            </tfoot>
                        </table>
                        <div class="row">
                            <div class="col-md-6">
                                <button data-toggle="modal" data-target="#importModal" class="btn btn-outline-primary"
                                        type="button"><i class="fa fa-pencil-square-o"
                                                         aria-hidden="true"></i> Nhập danh sách sinh viên mới...
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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
    </main>
    </div>
@endsection

@section('sub-javascript')
    <script type="text/javascript" src="{{ asset('template/js/plugins/jquery.dataTables.min.js') }} "></script>
    <script type="text/javascript" src="{{ asset('template/js/plugins/dataTables.bootstrap.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('template/js/plugins/bootstrap-notify.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('template/js/plugins/sweetalert.min.js') }}"></script>
    <script type="text/javascript">
    </script>


    <script>
        $(document).ready(function () {

            $("div#students_filter").hide();

            var oTable = $('#studentsEachList').DataTable({
                "processing": true,
                "serverSide": true,
                "ajax": {
                    "url": "{{ route('ajax-student-get-users') }}",
                    "dataType": "json",
                    "type": "POST",
                    "data": function (d) {
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
                    {data: "totalScore", name: "totalScore", searchable: false}
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
                "pageLength": 10
            });

            $('#search-form').on('submit', function (e) {
                oTable.draw();
                e.preventDefault();
            });


//            import
            $("#btn-import-student").click(function (e) {
                e.preventDefault();
                $('.form-row').find('span.messageErrors').remove();
                var $fileUpload = $("input[type='file']");
                if (parseInt($fileUpload.get(0).files.length) > 20) {
                    $('form#import-student-form').find('.fileImport').parents('.form-row').append('<span class="messageErrors" style="color:red">Chỉ được upload tối đa 20 tập tin</span>');
                } else {
                    $("#importModal").find("p.child-error").remove();
                    var formData = new FormData();
                    var fileImport = document.getElementById('fileImport');
                    var inss = fileImport.files.length;
                    for (var x = 0; x < inss; x++) {
                        file = fileImport.files[x];
                        formData.append("fileImport[]", file);
                    }
                    var url = $(this).attr('data-link');
                    $('.form-row').find('span.messageErrors').remove();
                    $.ajax({
                        type: "post",
                        url: url,
                        data: formData,
                        cache: false,
                        contentType: false,
                        // enctype: 'multipart/form-data',
                        processData: false,
                        beforeSend: function () {
                            $("#importModal").find("button#btn-import-student").prop('disabled', true);
                        },
                        success: function (result) {
                            $("#importModal").find("button#btn-import-student").prop('disabled', false);
                            if (result.status === false) {
                                //show error list fields
                                if (result.arrMessages !== undefined) {
                                    $.each(result.arrMessages, function (elementName, arrMessagesEveryElement) {
                                        $.each(arrMessagesEveryElement, function (messageType, messageValue) {
                                            $('form#import-student-form').find('.' + elementName).parents('.form-row').append('<span class="messageErrors" style="color:red">' + messageValue + '</span>');
                                        });
                                    });
                                }
                                if (result.errors !== undefined) {
                                    // console.log(result.errors);
                                    $('#importModal').find('div.alert-danger').show();
                                    $.each(result.errors, function (elementName, arrMessagesEveryElement) {
                                        $('#importModal').find('div.alert-danger').append("<p class='child-error'>" + arrMessagesEveryElement + "</p>");
                                    });
                                }
                            } else if (result.status === true) {
                                $('#importModal').find('.modal-body').html('<p>Upload Thành công</p>');
                                $("#importModal").find('.modal-footer').html('<button  class="btn btn-default" data-dismiss="modal">Đóng</button>');
                                $('#importModal').on('hidden.bs.modal', function (e) {
                                    location.reload();
                                });
                            }
                        }
                    });
                }
            });

            $('#myModal').on('hidden.bs.modal', function (e) {
                $("input[type=text],input[type=number], select").val('');
                $('.text-red').html('');
                $('.form-row').find('span.messageErrors').remove();
            });

        });
    </script>
@endsection
