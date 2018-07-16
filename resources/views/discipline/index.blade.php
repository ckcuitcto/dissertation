@extends('layouts.default')

@section('content')
    <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class="fa fa-laptop"></i> Quản lí kỷ luật </h1>
                <p>Trường Đại học Công nghệ Sài Gòn</p>
            </div>
            <ul class="app-breadcrumb breadcrumb">
                <li class="breadcrumb-item"><a href="{{route('home')}}"><i class="fa fa-home fa-lg"></i></a></li>
                <li class="breadcrumb-item"> Quản lí kỷ luật</li>
            </ul>
        </div>
        <div class="row">
            <div class="col-md-12 custom-quanly-taikhoan">
                <div class="tile">
                    <div class="col-md-12">
                        <div class="bs-component">
                            <ul class="nav nav-tabs">
                                <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#disciplines">Danh sách sinh viên bị kỷ luật</a></li>
                                <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#disciplinesReason">Danh sách lý do kỷ luật </a></li>
                            </ul>
                            <div class="tab-content" id="myTabContent">
                                <div class="tab-pane fade active show" id="disciplines">
                                    <br>
                                    <div class="tile-body">
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
                                            <i class="fa fa-download" aria-hidden="true"></i>Tải file mẫu nhập danh sách kỷ luật viên khóa mới
                                        </a>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="disciplinesReason">
                                    <br>
                                    <div class="tile-body">
                                        <table id="disciplineReasonTable" class="table table-bordered table-hover">
                                            <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Tiêu chí trừ điểm</th>
                                                <th>Lý do</th>
                                                <th>Số điểm</th>
                                                <th>Tác vụ</th>
                                            </tr>
                                            </thead>
                                        </table>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <button data-toggle="modal" data-target="#myModal" class="btn btn-primary"
                                                        id="btnAddsemester" type="button"><i class="fa fa-plus" aria-hidden="true"></i>Thêm
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

        <div class="modal fade" id="myModal" role="dialog">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Thêm Lý do kỷ luật</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span
                                    aria-hidden="true">×</span></button>
                    </div>
                    <div class="modal-body">
                        <form id="discipline-reason-form">
                            {!! csrf_field() !!}
                            <div class="row">
                                <div class="col-md-12">
                                    {{--<input type="hidden" class="form-control id" name="id" id="id">--}}
                                    <div class="form-row">
                                        <label for="evaluation_criteria_id">Tiêu chí sẽ bị trừ nếu vi phạm kỷ luật này</label>
                                        <select class="form-control evaluation_criteria_id" name="evaluation_criteria_id" id="evaluation_criteria_id">
                                            @foreach($evaluationCriterias as $value)
                                                <option value="{{ $value->id }}"> {{ $value->content }} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-row">
                                        <label for="score_minus">Số điểm bị trừ</label>
                                        <input type="number" class="form-control score_minus" name="score_minus" id="score_minus" min="0">
                                    </div>
                                    <div class="form-row">
                                        <label for="reason">Nội dung lý do vi phạm</label>
                                        <textarea class="form-control reason" name="reason" id="reason" rows="4" cols="4"></textarea>

                                    </div>
                                </div>
                            </div>
                        </form>
                        <div class="modal-footer">
                            <button data-link="{{ route('discipline-reason-store') }}" class="btn btn-primary"
                                    id="btn-save-discipline-reason" name="btn-save-discipline-reason" type="button">
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

        $('body').on('click', '.nav-item', function (e) {
            var tabs = $(this).children().attr('href');
            // $("div.tab-pane").removeClass('active');
            $("div.tab-pane").fadeOut(200);
            $("div" + tabs).fadeIn(200);
        });

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
                {data: "score_minus", name: "discipline_reasons.score_minus"},
                {data: "reason", name: "discipline_reasons.reason"},
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

        var disciplineReasonTable = $('#disciplineReasonTable').DataTable({
            "processing": true,
            "serverSide": true,
            "autoWidth": false,
            "ajax": {
                "url": "{{ route('ajax-get-discipline-reasons') }}",
                "dataType": "json",
                "type": "POST",
                "data": {
                    "_token": "{{ csrf_token() }}"
                }
            },
            "columns": [
                {data: "id", name: "discipline_reasons.id"},
                {data: "content", name: "evaluation_criterias.content"},
                {data: "reason", name: "discipline_reasons.reason"},
                {data: "score_minus", name: "discipline_reasons.score_minus"},
                {data: "action", name: "action",orderable: false, searchable: false},
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

        $('body').on('click', '#btn-save-discipline-reason', function (e) {
            var valueForm = $('form#discipline-reason-form').serialize();
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
                                    $('form#discipline-reason-form').find('.' + elementName).parents('.form-row').append('<span class="messageErrors" style="color:red">' + messageValue + '</span>');
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
                        disciplineReasonTable.draw();
                    }
                }
            });
        });

        $('body').on('click', 'button.update-discipline-reason', function (e) {
            var urlEdit = $(this).attr('data-edit-link');
            var urlUpdate = $(this).attr('data-update-link');
            var id = $(this).attr('data-id');
            $('.form-row').find('span.messageErrors').remove();
            $.ajax({
                type: "get",
                url: urlEdit,
                data: {id: id},
                dataType: 'json',
                success: function (result) {
                    if (result.status === true) {
                        if (result.disciplineReason !== undefined) {
                            $.each(result.disciplineReason, function (elementName, value) {
                                // alert(elementName + '- ' + value);
                                if (elementName === 'reason') {
                                    $("#discipline-reason-form").find('.' + elementName).html(value);
                                } else {
                                    $("#discipline-reason-form").find('.' + elementName).val(value);
                                }
                            });
                        }
                    }
                }
            });
            $('#myModal').find(".modal-title").text('Sửa lý do kỷ luật');
            $('#myModal').find(".modal-footer > button[name=btn-save-discipline-reason]").html('Sửa');
            $('#myModal').find(".modal-footer > button[name=btn-save-discipline-reason]").attr('data-link', urlUpdate);
            $('#myModal').modal('show');
        });

        $('body').on('click', 'button.delete-discipline-reason', function (e) {
            var id = $(this).attr("data-id");
            var url = $(this).attr('data-delete-link');
            swal({
                title: "Bạn chắc chưa?",
                text: "Bạn sẽ không thể khôi phục lại dữ liệu !!",
                type: "warning",
                showCancelButton: true,
                confirmButtonText: "Có, tôi chắc chắn!",
                cancelButtonText: "Không, Hủy dùm tôi!",
                closeOnConfirm: false,
                closeOnCancel: false
            }, function (isConfirm) {
                if (isConfirm) {
                    $.ajax({
                        url: url,
                        type: 'GET',
                        cache: false,
                        data: {"id": id},
                        success: function (data) {
                            if (data.status === true) {
                                swal("Deleted! ", "Đã lý do kỷ luật " + data.disciplineReason.reason , "success");
                                $('.sa-confirm-button-container').click(function () {
                                    disciplineReasonTable.draw();
                                })
                            } else {
                                swal("Cancelled", "Không tìm thấy lý do kỷ luật !!! :)", "error");
                            }
                        }
                    });
                } else {
                    swal("Đã hủy", "Đã hủy xóa lý do kỷ luật:)", "error");
                }
            });
        });

        $('#myModal').on('hidden.bs.modal', function (e) {
            $('#myModal').find("input[type=text],input[type=number],input[type=hidden]").val('');
            $('#myModal').find("textarea").html('');
            $('.text-red').html('');
            $('span.messageErrors').remove();
            $('#myModal').find(".modal-title").text('Thêm Lý do kỷ luật');
            $('#myModal').find(".modal-footer > button[name=btn-save-discipline-reason]").html('Thêm');
            $('#myModal').find(".modal-footer > button[name=btn-save-discipline-reason]").attr('data-link', "{{ route('discipline-reason-store') }}");
        });
    </script>
@endsection