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
                    <section class="invoice">
                        <div class="tile-body">
                            <table class="table table-hover table-bordered" id="proofsTable">
                                <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Tiêu chí</th>
                                    <th>Học kỳ</th>
                                    <th>Năm học</th>
                                    <th>Trạng thái</th>
                                    <th>Tác vụ</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <button class="btn btn-outline-success" id="createFile"
                                        data-toggle="modal" data-target="#addModal">
                                    <i class="fa fa-plus" aria-hidden="true"></i>Thêm minh chứng
                                </button>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
        <div class="modal fade" id="myModal" role="dialog">
            <div class="modal-dialog modal-lg" role="document">
                <div class="overlay">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Xem file minh chứng</h5>
                            <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">×</span></button>
                        </div>
                        <div class="modal-body body-view-file">
                            <div id="iframe-view-file" class="doc"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="addModal" role="dialog">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Chọn file minh chứng muốn thêm</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span
                                    aria-hidden="true">×</span></button>
                    </div>
                    <div class="modal-body">
                        <form id="upload-proof-form">
                            {!! csrf_field() !!}
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-row">
                                        <label for="evaluation_criteria">Chọn tiêu chí </label>
                                        {{--<div class="custom-select">--}}
                                            <select class=" form-control evaluation_criteria" name="evaluation_criteria" id="evaluationCriteria">
                                                <option value="0"> ---Chọn tiêu chí---</option>
                                                @foreach($evaluationCriterias as $value)
                                                    <option value="{{$value->id}}">{{$value->content}}</option>
                                                @endforeach
                                            </select>
                                        {{--</div>--}}
                                    </div>
                                    <div class="form-row">
                                        <label for="semester">Chọn học kì</label>

                                            <select class="form-control semester" name="semester" id="semester">
                                                <option value="0"> ---Chọn học kì---</option>
                                                @foreach($semesters as $value)
                                                    <option value="{{$value->id}}">{{ "Học kì $value->term. Năm học $value->year_from - $value->year_to" }}</option>
                                                @endforeach
                                            </select>
                                    </div>
                                    <div class="form-row">
                                        <label for="fileUpload">Chọn file minh chứng</label>
                                        <input type="file" multiple class="form-control fileUpload" name="fileUpload"
                                               id="fileUpload">
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button data-link="{{ route('proof-store') }}" class="btn btn-primary"
                                id="btn-upload-proof" name="btn-upload-proof" type="button">
                            Thêm
                        </button>
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Đóng</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="updateModal" role="dialog">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Sửa minh chứng</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span
                                    aria-hidden="true">×</span></button>
                    </div>
                    <div class="modal-body">
                        <form id="update-proof-form">
                            {!! csrf_field() !!}
                            <div class="row">
                                <input type="hidden" name="id" class="id">
                                <div class="col-md-12">
                                    <div class="form-row">
                                        <label for="evaluation_criteria_id">Chọn tiêu chí </label>
                                        <select class="form-control evaluation_criteria_id"
                                                name="evaluation_criteria_id"
                                                id="evaluation_criteria_id">
                                            <option value="0"> ---Chọn tiêu chí---</option>
                                            @foreach($evaluationCriterias as $value)
                                                <option value="{{$value->id}}">{{$value->content}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-row">
                                        <label for="semester_id">Chọn học kì</label>
                                        <select class="form-control semester_id" name="semester_id" id="semester_id">
                                            <option value="0"> ---Chọn học kì---</option>
                                            @foreach($semesters as $value)
                                                <option value="{{$value->id}}">{{ "Học kì $value->term. Năm học $value->year_from - $value->year_to" }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-row">
                                        <div class="modal-body body-view-file">
                                            <div id="iframe-view-file" class="doc"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>

                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary"
                                id="btn-update-proof" name="btn-update-proof" type="button">
                            Sửa
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
        // đoạn code để chạy datatable
        var oTable = $('#proofsTable').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "{{ route('ajax-get-proofs') }}",
                "dataType": "json",
                "type": "POST",
                "data": {
                    "_token": "{{ csrf_token() }}"
                }
            },
            "columns": [
                {data: "proofId", name: "proofs.id"},
                {data: "content", name: "evaluation_criterias.content"},
                {data: "term", name: "semesters.term"},
                {data: "semesterYear", name: "semesters.year_from"},
                {data: "valid", name: "proofs.valid"},
                {data: 'action', name: 'action', orderable: false, searchable: false}
            ],
            "language": {
                "lengthMenu": "Hiển thị _MENU_ bản ghi mỗi trang",
                "zeroRecords": "Không có bản ghi nào!",
                "info": "Hiển thị trang _PAGE_ của _PAGES_",
                "infoEmpty": "Không có bản ghi nào!!!",
                "infoFiltered": "(Đã lọc từ tổng _MAX_ bản ghi)",
                processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Tải dữ liệu...</span>'
            },
            "pageLength": 10
        });
        // kết thúc đoạn code chạy datatable

        $('body').on('click', 'a#proof-view-file', function (e) {
            var thisproof = $(this);
            var id = thisproof.attr('data-proof-id');
            var url = thisproof.attr('data-get-file-link');
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

        $('body').on('change', 'input#fileUpload', function (e) {
            // $('input#fileUpload').change(function (e) {
            e.preventDefault();
            var urlCheckFile = "{{ route('evaluation-form-upload') }}";
            var formData = new FormData();
            var fileUpload = $(this);
            var countFile = fileUpload[0].files.length;
            for (var x = 0; x < countFile; x++) {
                file = fileUpload[0].files[x];
                formData.append("fileUpload[]", file);
            }
            $('.form-row').find('span.messageErrors').remove();
            $.ajax({
                type: "post",
                url: urlCheckFile,
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function (result) {
                    if (result.status === false) {
                        //show error list fields
                        if (result.arrMessages !== undefined) {
                            $.each(result.arrMessages, function (elementName, arrMessagesEveryElement) {
                                $.each(arrMessagesEveryElement, function (messageType, messageValue) {
                                    fileUpload.after('<span class="messageErrors" style="color:red"><br>' + messageValue + '</span>');
                                });
                            });
                        }
                    }
                }
            });
        });
    </script>
    <script src="{{ asset('js/web/proof/index.js') }}"></script>
@endsection