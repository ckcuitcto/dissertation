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
                                                   name="year_from"/>
                                            <span class="input-group-addon">to</span>
                                            <input type="year" class="input-sm form-control year_to" name="year_to"
                                                   id="year_to"/>
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
                                    <div class="form-row">
                                        <label for="">Thời gian khiếu nại</label>
                                        <div class="input-group">
                                            <input type="text"
                                                   class="input-sm form-control date_start_to_request_re_mark"
                                                   id="date_start_to_request_re_mark"
                                                   name="date_start_to_request_re_mark"/>
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
                                        <input type="number" class="form-control term" name="term" id="term" max="3"
                                               min="1" placeholder="Học kì">
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
                "zeroRecords": "Không có bản ghi nào!",
                "info": "Hiển thị trang _PAGE_ của _PAGES_",
                "infoEmpty": "Không có bản ghi nào!!!",
                "infoFiltered": "(Đã lọc từ tổng _MAX_ bản ghi)",
                processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Tải dữ liệu...</span>'
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
        $('#myModal').on('hidden.bs.modal', function (e) {
            $('#myModal').find("input[type=text],input[type=number],input[type=year], select").val('').prop('disabled', false).prop('readonly', false);
            $('.text-red').html('');
            $('span.messageErrors').remove();
            $('#myModal').find(".modal-title").text('Thêm mới học kì');
            $('#myModal').find(".modal-footer > button[name=btn-save-semester]").html('Thêm');
            $('#myModal').find(".modal-footer > button[name=btn-save-semester]").attr('data-link', "{{ route('semester-store') }}");
        });

    </script>
    <script src="{{ asset('js/web/semester/semester.js') }}"></script>
@endsection


