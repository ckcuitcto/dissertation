@extends('layouts.default')
@section('content')
    <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class="fa fa-file-text-o"></i> Danh sách học kỳ </h1>
                <p>Trường Đại học Công nghệ Sài Gòn</p>
            </div>
            <ul class="app-breadcrumb breadcrumb">
                <li class="breadcrumb-item"><a href="{{route('home')}}"><i class="fa fa-home fa-lg"></i></a></li>
                <li class="breadcrumb-item active"> Danh sách học kỳ</li>
            </ul>
        </div>
        <div class="row">
            <div class="col-md-12 custom-quanly-taikhoan">
                <div class="tile">
                    <div class="tile-body">
                        <table class="table table-hover table-bordered" id="semestersTable">
                            <thead>
                            <tr>
                                <th>STT</th>
                                <th>Học kì</th>
                                <th>Năm học</th>
                                <th>Ngày bắt đầu chấm</th>
                                <th>Ngày kết thúc chấm</th>
                                <th>Tác vụ</th>
                            </tr>
                            </thead>
                        </table>
                        <div class="row">
                            <div class="col-md-6">
                                <button data-toggle="modal" data-target="#myModal" class="btn btn-primary"
                                        id="btnAddsemester" type="button"><i class="fa fa-plus"
                                                                             aria-hidden="true"></i>Thêm
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="myModal" role="dialog">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Thêm mới học kì</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span
                                    aria-hidden="true">×</span></button>
                    </div>
                    <div class="modal-body">
                        <form id="semester-form">
                            {!! csrf_field() !!}
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-row">
                                        <label for="year">Năm học</label>
                                        <div class="input-group">
                                            <input type="year" class="input-sm form-control year_from" id="year_from"
                                                   name="year_from" />
                                            <span class="input-group-addon">to</span>
                                            <input type="year" class="input-sm form-control year_to" name="year_to" id="year_to"/>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <label for="">Thời gian của học kì</label>
                                        <div class="input-group">
                                            {{--<label for="date_start">Thời gian của học kì</label>--}}
                                            <input type="text" class="input-sm form-control date_start"
                                                   id="date_start" name="date_start"/>
                                            <span class="input-group-addon">to</span>
                                            {{--<label for="date_end">Thời gian của học kì</label>--}}
                                            <input type="text" class="input-sm form-control date_end"
                                                   id="date_end" name="date_end"/>
                                        </div>
                                    </div>
                                    {{--<div class="form-row">--}}
                                        {{--<label for="">Ngày chấm</label>--}}
                                        {{--<div class="input-group">--}}
                                            {{--<input type="text" class="input-sm form-control date_start_to_mark"--}}
                                                   {{--id="date_start_to_mark" name="date_start_to_mark"/>--}}
                                            {{--<span class="input-group-addon">to</span>--}}
                                            {{--<input type="text" class="input-sm form-control date_end_to_mark"--}}
                                                   {{--id="date_end_to_mark" name="date_end_to_mark"/>--}}
                                        {{--</div>--}}
                                    {{--</div>--}}
                                    <div class="form-row">
                                        <label for="">Thời gian khiếu nại</label>
                                        <div class="input-group">
                                            <input type="text" class="input-sm form-control date_start_to_request_re_mark"
                                                   id="date_start_to_request_re_mark" name="date_start_to_request_re_mark"/>
                                            <span class="input-group-addon">to</span>
                                            <input type="text" class="input-sm form-control date_end_to_request_re_mark"
                                                   id="date_end_to_request_re_mark" name="date_end_to_request_re_mark"/>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <label for="">Thời gian chấm lại khiếu nại</label>
                                        <div class="input-group">
                                            <input type="text" class="input-sm form-control date_start_to_re_mark"
                                                   id="date_start_to_re_mark" name="date_start_to_re_mark"/>
                                            <span class="input-group-addon">to</span>
                                            <input type="text" class="input-sm form-control date_end_to_re_mark"
                                                   id="date_end_to_re_mark" name="date_end_to_re_mark"/>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <label for="term">Học kì</label>
                                        <input type="number" class="form-control term" name="term" id="term" max="3" min="1" placeholder="Học kì">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    @foreach($rolesCanMark as $key => $role)
                                        <div class="form-row">
                                            <label for="mark_date">Thời gian chấm của {{ $role->display_name }}</label>
                                            <div class="input-group">
                                                <input type="text"
                                                       class="input-sm form-control {{ 'date_start_to_mark_'.$role->id }}"
                                                       id="{{ 'date_start_to_mark_'.$role->id }}"
                                                       name="{{ 'date_start_to_mark_'.$role->id }}"/>
                                                <span class="input-group-addon">to</span>
                                                <input type="text"
                                                       class="input-sm form-control {{ 'date_end_to_mark_'.$role->id }}"
                                                       id="{{ 'date_end_to_mark_'.$role->id }}"
                                                       name="{{ 'date_end_to_mark_'.$role->id }}"/>
                                            </div>
                                        </div>
                                        
                                    @endforeach
                                    
                                </div>
                            </div>
                        </form>
                        <div class="modal-footer">
                            <button data-link="{{ route('semester-store') }}" class="btn btn-primary"
                                    id="btn-save-semester" name="btn-save-semester" type="button">
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
    {{--<script type="text/javascript" src="{{ asset('template/js/plugins/jquery.dataTables.min.js') }} "></script>--}}
    {{--<script type="text/javascript" src="{{ asset('template/js/plugins/dataTables.bootstrap.min.js') }}"></script>--}}
    <script type="text/javascript" src="{{ asset('template/js/plugins/bootstrap-notify.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('template/js/plugins/sweetalert.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/semester.js') }}"></script>


    <script>
        var oTable = $('#semestersTable').DataTable({
            "processing": true,
            "serverSide": true,
            "autoWidth": false,
            "ajax": {
                "url": "{{ route('ajax-get-semesters') }}",
                "dataType": "json",
                "type": "POST",
                "data": {
                    "_token": "{{ csrf_token() }}"
                }
            },
            "columns": [
                {data: "id", name: "id"},
                {data: "term", name: "term"},
                {data: "semesterYear", name: "year_from"},
                {data: "date_start_to_mark", name: "date_start_to_mark"},
                {data: "date_end_to_mark", name: "date_end_to_mark"},
                {data: 'action', name: 'action', orderable: false, searchable: false}
            ],
            "language": {
                "lengthMenu": "Hiển thị _MENU_ bản ghi mỗi trang",
                // "zeroRecords": "Không có bản ghi nào!",
                // "info": "Hiển thị trang _PAGE_ của _PAGES_",
                "infoEmpty": "Không có bản ghi nào!!!",
                "infoFiltered": "(Đã lọc từ _MAX_ total bản ghi)"
            },
            "pageLength": 10
        });


        @foreach($rolesCanMark as $key => $role)
        $("input#date_start_to_mark_{{$role->id}}").datepicker({
            todayBtn: "linked",
            language: "vi",
            format: "dd/mm/yyyy",
            clearBtn: true,
            orientation: "bottom right",
            autoclose: true,
            toggleActive: true,
            todayHighlight: true
        });
        $("input#date_end_to_mark_{{$role->id}}").datepicker({
            todayBtn: "linked",
            language: "vi",
            format: "dd/mm/yyyy",
            clearBtn: true,
            orientation: "bottom right",
            autoclose: true,
            toggleActive: true,
            todayHighlight: true
        });
        @endforeach

        $(document).ready(function () {
            $('body').on('click', 'a.update-semester', function (e) {
                var urlEdit = $(this).attr('data-semester-edit-link');
                var urlUpdate = $(this).attr('data-semester-update-link');
                var id = $(this).attr('data-semester-id');
                $('.form-row').find('span.messageErrors').remove();
                $.ajax({
                    type: "get",
                    url: urlEdit,
                    data: {id: id},
                    dataType: 'json',
                    success: function (result) {
                        if (result.status === true) {
                            if (result.semester !== undefined) {
                                $.each(result.semester, function (elementName, value) {
                                    // alert(elementName + '- ' + value);
                                    if(elementName === 'year_from' || elementName ==='year_to'){
                                        $('.' + elementName).val(value).prop('disabled',true);
                                    }else if (elementName === 'term') {
                                        $('.' + elementName).val(value).prop('readonly',true);
                                    } else {
                                        $('.' + elementName).datepicker('setDate', value);
                                    }
                                });
                            }
                            if (result.marktime !== undefined) {
                                $.each(result.marktime, function (elementName, value) {
                                    var role_id = value.role_id;
                                    $.each(value, function (messageType, messageValue) {
                                        // alert(messageType + '-' + messageValue);
                                        if (messageType === 'mark_time_start') {
                                            $('.date_start_to_mark_' + role_id).datepicker('setDate', messageValue);
                                        }
                                        if (messageType === 'mark_time_end') {
                                            $('.date_end_to_mark_' + role_id).datepicker('setDate', messageValue);
                                        }
                                    });
                                });
                            }
                        }
                    }
                });
                $('#myModal').find(".modal-title").text('Sửa thông tin học kì');
                $('#myModal').find(".modal-footer > button[name=btn-save-semester]").html('Sửa');
                $('#myModal').find(".modal-footer > button[name=btn-save-semester]").attr('data-link', urlUpdate);
                $('#myModal').modal('show');
            });

            $('body').on('click', '#btn-save-semester', function (e) {
                var valueForm = $('form#semester-form').serialize();
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
                                        $('form#semester-form').find('.' + elementName).parents('.form-row').append('<span class="messageErrors" style="color:red">' + messageValue + '</span>');
                                    });
                                });
                            }
                        } else if (result.status === true) {
                            $.notify({
                                title: "Thêm học kì thành công",
                                message: ":D",
                                icon: 'fa fa-check'
                            },{
                                type: "success"
                            });
                            $('div#myModal').modal('hide');
                            oTable.draw();
                        }
                    }
                });
            });

            $('body').on('click', 'a#destroy-semester', function (e) {
                var id = $(this).attr("data-semester-id");
                var url = $(this).attr('data-semester-delete-link');
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
                                    swal("Deleted! ", "Đã xóa học kì " + data.semester.term + " năm học "+ data.semester.year_from +"-"+ data.semester.year_to, "success");
                                    $('.sa-confirm-button-container').click(function () {
                                        oTable.draw();
                                    })
                                } else {
                                    swal("Cancelled", "Không tìm thấy học kì !!! :)", "error");
                                }
                            }
                        });
                    } else {
                        swal("Đã hủy", "Đã hủy xóa học kì:)", "error");
                    }
                });
            });

            $('#myModal').on('hidden.bs.modal', function (e) {
                $('#myModal').find("input[type=text],input[type=number],input[type=year], select").val('').prop('disabled',false).prop('readonly',false);
                $('.text-red').html('');
                $('span.messageErrors').remove();
                $('#myModal').find(".modal-title").text('Thêm mới học kì');
                $('#myModal').find(".modal-footer > button[name=btn-save-semester]").html('Thêm');
                $('#myModal').find(".modal-footer > button[name=btn-save-semester]").attr('data-link', "{{ route('semester-store') }}");
            });

        });
    </script>
@endsection


