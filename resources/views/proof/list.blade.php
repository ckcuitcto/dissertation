@extends('layouts.default')



@section('content')

    <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class="fa fa-calendar-o"></i> Quản lý minh chứng</h1>
                <p>Trường Đại học Công nghệ Sài Gòn</p>
            </div>
            <ul class="app-breadcrumb breadcrumb">
                <li class="breadcrumb-item"><a href="{{route('home')}}"><i class="fa fa-home fa-lg"></i></a></li>
                <li class="breadcrumb-item"> Quản lý minh chứng</li>
            </ul>
        </div>
        <div class="row">
            <div class="col-md-12 custom-quanly-taikhoan">
                <div class="tile">
                    {{--<section class="invoice">--}}
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
                                    <button class="btn btn-primary" type="submit"><i class="fa fa-fw fa-lg fa-search"></i>Tìm kiếm
                                    </button>
                                </div>
                            </form>
                        </div>
                        <div class="tile-body">
                            <table class="table table-hover table-bordered" id="proofsOfStudentTable">
                                <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Sinh viên</th>
                                    <th>Lớp</th>
                                    <th>Tiêu chí</th>
                                    <th>Học kỳ</th>
                                    <th>Năm học</th>
                                    <th>Trạng thái (1 hoặc 0)</th>
                                    <th>Tác vụ</th>
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
                        </div>
                        {{--<div class="row">--}}
                            {{--<div class="col-md-6">--}}
                                {{--<button class="btn btn-outline-success" id="createFile"--}}
                                        {{--data-toggle="modal" data-target="#addModal">--}}
                                    {{--<i class="fa fa-plus" aria-hidden="true"></i>Thêm minh chứng--}}
                                {{--</button>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                    {{--</section>--}}
                </div>
            </div>
        </div>
        <div class="modal fade" id="myModal" role="dialog">
            <div class="modal-dialog modal-lg custom-modal-popup" role="document">
                <div class="overlay">
                    <form id="proof-form" method="post">
                        {!! csrf_field() !!}
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Xem file minh chứng</h5>
                                <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span
                                            aria-hidden="true">×</span></button>
                            </div>
                            <div class="modal-body">
                                <div id="iframe-view-file">
                                    {{--<iframe id="frame-view-file" class="doc"></iframe>--}}
                                </div>
                                <input type="hidden" class="id" name="id" id="proofId">
                                {{--khi bấm vào modal. thì chỉ những ng khác k phải là chủ của phiếu mới đc chỉnh sửa file có hợp lệ hay k--}}
                                {{-- và role hiện tại có thể chấm thì ms có thể sửa trạng thái--}}
                                {{--                                @if( $evaluationForm->Student->User->users_id != $user->users_id)--}}
                                {{--                                @if( $evaluationForm->Student->User->users_id != $user->users_id AND $currentRoleCanMark->weight == $user->Role->weight)--}}
                                @if( ROLE_COVANHOCTAP <= $userLogin->Role->weight)
                                    <div class="row edit-status-proof" style="display: none">
                                        <div class="col-md-2">
                                            <fieldset class="form-group">
                                                <label for="">File hợp lệ?</label>
                                                <div class="form-check">
                                                    <label class="form-check-label">
                                                        <input class="form-check-input valid" id="valid" type="radio"
                                                               name="valid" value="1">Có
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <label class="form-check-label">
                                                        <input class="form-check-input valid" id="invalid" type="radio"
                                                               name="valid" value="0">Không
                                                    </label>
                                                </div>
                                            </fieldset>
                                        </div>
                                        <div class="col-md-7">
                                            <div class="form-group" id="textarea-note" style="display: none;">
                                                <label for="note">Ghi chú</label>
                                                <textarea class="form-control note" name="note" id="note"
                                                          rows="3"></textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <p></p>
                                                <button class="btn btn-primary" id="btn-update-valid-proof-file"
                                                        name="btn-update-valid-proof-file">Sửa
                                                </button>
                                                <button class="btn btn-secondary" id="closeForm" type="button"
                                                        data-dismiss="modal">
                                                    Đóng
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>
@endsection

@section('sub-javascript')
    <script>
        $('#myModal').on('hidden.bs.modal', function (e) {
            $('#myModal').find('div#iframe-view-file').html('');
            $('#myModal').find('textarea.note').html('');
            $('#myModal').find("input[type=text],input[type=number], select").val('');
            $('form#proof-form').find('p.note-for-student').html('');
            $('span.messageErrors').remove();
            $('#myModal').find("#note").html('');
        });

        $('body').on('change', 'input.valid', function (e) {
            if ($(this).val() == 1) {
                $("form#proof-form").find('#textarea-note').hide();
            } else {
                $("form#proof-form").find('#textarea-note').show();
            }
        });
        $('body').on('submit', 'form#proof-form', function (e) {
            e.preventDefault();
            var formData = new FormData(this);
            var url = $(this).attr("data-link");
            var proofId = formData.get('id');
            // console.log(formData.get('valid'));
            $('span.messageErrors').remove();
            $.ajax({
                type: "post",
                url: url,
                data: formData,
                dataType: 'json',
                cache: false,
                contentType: false,
                processData: false,
                success: function (result) {
                    // console.log(result);
                    if (result.status === true) {
                        if(formData.get('valid') == 1){
                            $("i.proofId_"+proofId).removeClass('fa-times').addClass('fa-check');
                            $("i.proofId_"+proofId).parent().removeClass('btn-danger').addClass('btn-primary');
                        }else{
                            $("i.proofId_"+proofId).removeClass('fa-check').addClass('fa-times');
                            $("i.proofId_"+proofId).parent().removeClass('btn-primary').addClass('btn-danger');
                        }
                        oTable.draw();
                        $('#myModal').modal('hide');
                        $.notify({
                            title: "Cập nhật thành công : ",
                            message: ":D",
                            icon: 'fa fa-check'
                        }, {
                            type: "info"
                        });
                    } else {
                        $('form#proof-form').find('.note').parents('.form-group').append('<span class="messageErrors" style="color:red">' + result.arrMessages.note + '</span>');
                    }
                }
            });
        });

        $('body').on('click', 'a.proof-view-file', function (e) {
            var thisproof = $(this);
            var name = thisproof.attr('data-proof-file-path');
            var id = thisproof.attr('data-proof-id');
            var url = thisproof.attr('data-get-file-link');
            var urlUpdateProofFile = thisproof.attr('data-link-update-proof-file');

            $.ajax({
                url: url,
                type: 'POST',
                dataType: 'json',
                data: {id: id},
                success: function (data) {
                    if (data.status === true && data.proof !== undefined) {
                        $("form#proof-form").attr("data-link", urlUpdateProofFile);
                        $.each(data.proof, function (elementName, value) {
                            if (elementName === 'name') {
                                var fileType = value.lastIndexOf(".");
                                var type = value.substring(fileType + 1, value.length);
                                // kiểm tra file. nếu là file pdf thì bỏ vào iframe. nếu là file khác(ảnh) thì bỏ vào img rồi cho lên
                                var urlFile = '{{ asset("upload/proof/") }}' + '/' + value;
                                if (type === "pdf") {
                                    var contentView = '<iframe class="doc" src="' + urlFile + '"></iframe>';
                                } else {
                                    var contentView = "<img src='" + urlFile + "'> ";
                                }
                                $('div#iframe-view-file').html(contentView);

                            } else if (elementName === 'valid') {
                                if (value == 1) {
                                    $('form#proof-form').find('#valid').prop('checked', true);
                                    $("form#proof-form").find('#textarea-note').hide();
                                } else if (value == 0) {
                                    $('form#proof-form').find('#invalid').prop('checked', true);
                                    $("form#proof-form").find('#textarea-note').show();
                                } else {
                                    $('form#proof-form').find('#valid').attr('checked', true);
                                    $("form#proof-form").find('#textarea-note').hide();
                                }
                            } else if (elementName === 'note') {
                                if (data.proof.valid === 0) {
                                    // $('form#proof-form').find('p.note-for-student').html(value);
                                    $('form#proof-form').find('textarea.note').html(value);
                                }
                            } else {
                                $('form#proof-form').find('.' + elementName).val(value);
                            }
                        });
                        $('#myModal').modal('show');
                    }
                }
            });
        });

        // đoạn code để chạy datatable
        var oTable = $('#proofsOfStudentTable').DataTable({
            "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Tất cả"]],
            "autoWidth": false,
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "{{ route('ajax-get-proofs-of-student') }}",
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
                {data: "userId", name: "students.user_id"},
                {data: "userName", name: "users.name"},
                {data: "className", name: "classes.name"},
                {data: "content", name: "evaluation_criterias.content"},
                {data: "term", name: "semesters.term"},
                {data: "semesterYear", name: "semesters.year_from"},
                {data: "valid", name: "proofs.valid"},
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
        // kết thúc đoạn code chạy datatable

        $('body').on('click', 'a.proof-view-file', function (e) {
            var thisproof = $(this);
            var id = thisproof.attr('data-proof-id');
            var url = thisproof.attr('data-get-file-link');
            var canEdit = thisproof.attr('data-can-edit');

            $.ajax({
                url: url,
                type: 'POST',
                dataType: 'json',
                data: {id: id},
                success: function (data) {
                    if (data.status === true && data.proof !== undefined) {
                        // $("form#proof-form").attr("data-link", urlDeleteProofFile);
                        $.each(data.proof, function (elementName, value) {
                            if (elementName === 'name') {
                                var fileType = value.lastIndexOf(".");
                                var type = value.substring(fileType + 1, value.length);

                                // kiểm tra file. nếu là file pdf thì bỏ vào iframe. nếu là file khác(ảnh) thì bỏ vào img rồi cho lên
                                var urlFile = '{{ asset("upload/proof/") }}' + '/' + value;
                                if (type === "pdf") {
                                    var contentView = '<iframe class="doc" src="' + urlFile + '"></iframe>';
                                } else {
                                    var contentView = "<img src='" + urlFile + "'> ";
                                }
                                $("#myModal").find('div#iframe-view-file').html(contentView);
                            }
                        });
                        if(canEdit == 2){
                            $("#myModal").find('div.edit-status-proof').hide();
                        }else{
                            $("#myModal").find('div.edit-status-proof').show();
                        }
                        $('#myModal').modal('show');
                    }
                }
            });
        });

        $('body').on('click', 'button#proof-view-update-file', function (e) {

            var thisproof = $(this);
            var id = thisproof.attr('data-proof-id');
            var url = thisproof.attr('data-get-file-link');
            var urlUpdate = thisproof.attr('data-link-update-proof-file');
            $.ajax({
                url: url,
                type: 'POST',
                dataType: 'json',
                data: {id: id},
                success: function (data) {
                    if (data.status === true && data.proof !== undefined) {
                        // $("form#proof-form").attr("data-link", urlDeleteProofFile);
                        $.each(data.proof, function (elementName, value) {
                            if (elementName === 'name') {
                                var fileType = value.lastIndexOf(".");
                                var type = value.substring(fileType + 1, value.length);

                                // kiểm tra file. nếu là file pdf thì bỏ vào iframe. nếu là file khác(ảnh) thì bỏ vào img rồi cho lên
                                var urlFile = '{{ asset("upload/proof/") }}' + '/' + value;
                                if (type === "pdf") {
                                    var contentView = '<iframe class="doc" src="' + urlFile + '"></iframe>';
                                } else {
                                    var contentView = "<img src='" + urlFile + "'> ";
                                }
                                $("#updateModal").find('div#iframe-view-file').html(contentView);
                            } else if (elementName === 'semester_id' || elementName === 'evaluation_criteria_id') {
                                if (value === null) {
                                    $("#updateModal").find('select.' + elementName).val(0);
                                } else {
                                    $("#updateModal").find('select.' + elementName).val(value);
                                }
                            } else {
                                $("#updateModal").find('.' + elementName).val(value);
                            }
                        });
                        $('#updateModal').find("button#btn-update-proof").attr('data-link', urlUpdate);
                        $('#updateModal').modal('show');
                    }
                }
            });
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
    {{--<script src="{{ asset('js/web/proof/index.js') }}"></script>--}}
@endsection