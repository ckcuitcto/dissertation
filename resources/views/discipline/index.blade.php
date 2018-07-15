@extends('layouts.default')

@section('content')
    <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class="fa fa-laptop"></i> Danh sách kỷ luật </h1>
                <p>Trường Đại học Công nghệ Sài Gòn</p>
            </div>
            <ul class="app-breadcrumb breadcrumb">
                <li class="breadcrumb-item"><a href="{{route('home')}}"><i class="fa fa-home fa-lg"></i></a></li>
                <li class="breadcrumb-item"> Danh sách kỷ luật</li>
            </ul>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="tile">
                    <table id="disciplineTable" class="table table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Sinh viên</th>
                            <th>Học kì</th>
                            <th>Số điểm</th>
                            <th>Lí do</th>
                        </tr>
                        </thead>
                    </table>
                    <button data-toggle="modal" data-target="#importModal" class="btn btn-outline-primary"
                            type="button"><i class="fa fa-pencil-square-o"
                                             aria-hidden="true"></i> Nhập danh sách kỉ luật
                    </button>
                    <a href="{{ asset('upload/file_mau/danh_sach_ki_luat.xlsx') }}" class="btn btn-outline-success">
                        <i class="fa fa-download" aria-hidden="true"></i>Tải file mẫu nhập danh sách kỷ luật
                        viên khóa mới
                    </a>
                </div>
            </div>
        </div>

        <div class="modal fade" id="importModal" role="dialog">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Chọn file excel muốn nhập danh sách kỉ luật</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span
                                    aria-hidden="true">×</span></button>
                    </div>
                    <div class="modal-body">
                        <form id="import-form">
                            {!! csrf_field() !!}
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-row">
                                        <label for="semester_id">Chọn học kì</label>
                                        <select name="semester_id" id="semester_id" class="form-control">
                                            @foreach($semesters as $value)
                                                <option value="{{ $value->id }}">{{ "Học kì ".$value->term." năm học ".$value->year_from."-".$value->year_to }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-row">
                                        <label for="fileImport">Chọn file excel</label>
                                        <input type="file" class="form-control fileImport" name="fileImport"
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
                        <button data-link="{{ route('import-discipline') }}" class="btn btn-primary"
                                id="btn-import" name="btn-import" type="button">
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
        var oTable = $('#disciplineTable').DataTable({
            "processing": true,
            "serverSide": true,
            "autoWidth": false,
            "ajax": {
                "url": "{{ route('ajax-get-discipline') }}",
                "dataType": "json",
                "type": "POST",
                "data": {
                    "_token": "{{ csrf_token() }}"
                }
            },
            "columns": [
                {data: "id", name: "disciplines.id"},
                {data: "userName", name: "users.name"},
                {data: "semester", name: "semester.year_from"},
                {data: "score_minus", name: "disciplines.score_minus"},
                {data: "reason", name: "disciplines.reason"},
            ],
            "language": {
                "lengthMenu": "Hiển thị _MENU_ bản ghi mỗi trang",
                "zeroRecords": "Không có bản ghi nào!",
                "info": "Hiển thị trang _PAGE_ của _PAGES_",
                "infoEmpty": "Không có bản ghi nào!!!",
                "infoFiltered": "(Đã lọc từ _MAX_ total bản ghi)",
                processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Tải dữ liệu...</span>'
            },
            "pageLength": 10
        });

        $('body').on('click', '#btn-import', function (e) {
            e.preventDefault();
            $("#importModal").find("p.child-error").remove();
            var formData = new FormData();
            var fileImport = document.getElementById('fileImport');
            var semester_id = $('#semester_id').val();
            // var inss = fileImport.files.length;
            // for (var x = 0; x < inss; x++) {
                file = fileImport.files[0];
                formData.append("fileImport", file);
                formData.append("semester_id", semester_id);
            // }
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
                    $('#ajax_loader').show();
                },
                success: function (result) {
                    $('#ajax_loader').hide();
                    if (result.status === false) {
                        //show error list fields
                        if (result.arrMessages !== undefined) {
                            $.each(result.arrMessages, function (elementName, arrMessagesEveryElement) {
                                $.each(arrMessagesEveryElement, function (messageType, messageValue) {
                                    $('form#import-form').find('.' + elementName).parents('.form-row').append('<span class="messageErrors" style="color:red">' + messageValue + '</span>');
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
                        $.notify({
                            title: " Upload Thành công ",
                            message: ":D",
                            icon: 'fa fa-check'
                        }, {
                            type: "success"
                        });
                        $('div#importModal').modal('hide');
                        oTable.draw();
                    }
                }
            });
        });
    </script>
@endsection