@extends('layouts.default')

@section('content')
    <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class="fa fa-laptop"></i> Danh sách yêu cầu phúc khảo</h1>
                <p>Trường Đại học Công nghệ Sài Gòn</p>
            </div>
            <ul class="app-breadcrumb breadcrumb">
                <li class="breadcrumb-item"><a href="{{route('home')}}"><i class="fa fa-home fa-lg"></i></a></li>
                <li class="breadcrumb-item"> Danh sách yêu cầu phúc khảo</li>
            </ul>
        </div>
        <div class="row">
            <div class="col-md-12 custom-quanly-taikhoan">
                <div class="tile">
                    <table id="remaking-table" class="table table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Sinh viên</th>
                            <th>Lớp</th>
                            <th>Học kì</th>
                            <th>Năm học</th>
                            <th>Trạng thái</th>
                            <th>Ngày tạo</th>
                            <th>Tác vụ</th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
        <div class="modal fade" id="myModal" role="dialog">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Trả lời chấm phúc khảo</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span
                                    aria-hidden="true">×</span></button>
                    </div>
                    <div class="modal-body">
                        <form id="remarking-form">
                            {!! csrf_field() !!}
                            <div class="col-md-12">
                                <h3 class="tile-title">Lý do phúc khảo</h3>
                                <input type="hidden" class="id">
                                <div class="tile-body">
                                    <div class="form-group">
                                        <span class="remarking_reason"></span>
                                    </div>
                                    <div class="form-group">
                                        <textarea class="form-control remarking_reply" id="remarking_reply" rows="4" name="remarking_reply" placeholder="Nội dung trả lời chấm phúc khảo"></textarea>
                                    </div>
                                    <fieldset class="form-group">
                                        <legend>Trạng thái</legend>
                                        <div class="form-check">
                                            <label class="form-check-label">
                                                <input class="form-check-input status" id="status" type="radio" name="status" value="{{HANDLE}}"> Đang xử lí
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <label class="form-check-label">
                                                <input class="form-check-input status" id="status" type="radio" name="status" value="{{RESOLVED}}"> Đã giải quyết
                                            </label>
                                        </div>
                                    </fieldset>
                                </div>
                            </div>
                        </form>
                        <div class="modal-footer">
                            <button data-link="" class="btn btn-primary"
                                    id="btn-reply-remaking" name="btn-reply-remaking" type="button">
                                <i class="fa fa-fw fa-lg fa-check-circle"></i>Gửi
                            </button>
                            <button class="btn btn-secondary" id="closeForm" type="button" data-dismiss="modal">
                                <i class="fa fa-fw fa-lg fa-times-circle"></i>Đóng
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
        var oTable = $('#remaking-table').DataTable({
            "processing": true,
            "serverSide": true,
            "autoWidth": false,
            "ajax": {
                "url": "{{ route('ajax-remakings') }}",
                "dataType": "json",
                "type": "POST",
                "data": {
                    "_token": "{{ csrf_token() }}"
                }
            },
            "columns": [
                {data: "userId", name: "students.user_id"},
                {data: "userName", name: "users.name"},
                {data: "className", name: "classes.name"},
                {data: "term", name: "semesters.term"},
                {data: "semesterYear", name: "semesters.year_from"},
                {data: "status", name: "remakings.status"},
                {data: "created_at", name: "remakings.created_at"},
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
            "pageLength": 25
        });

        @if(!empty($userId))
            $('input[type="search"]').val("{{ $userId }}").keyup();
        @endif

    </script>
    <script src="{{ asset('js/web/remaking/index.js') }}"></script>
@endsection