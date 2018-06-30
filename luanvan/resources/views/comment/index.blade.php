@extends('layouts.default')
@section('content')
    <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class="fa fa-file-text-o"></i> Danh sách ý kiến</h1>
                <p>Trường Đại học Công nghệ Sài Gòn</p>
            </div>
            <ul class="app-breadcrumb breadcrumb side">
                <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="fa fa-home fa-lg"></i></a></li>
                <li class="breadcrumb-item active"> Danh sách ý kiến</li>
            </ul>
        </div>
        <div class="row">
            <div class="col-md-12">
                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif
                <div class="tile">
                    <div class="tile-body">
                        <table class="table table-hover table-bordered" id="commentsTable">
                            {{--@if($user->Role->id <= 3)--}}
                            <thead>
                            <tr>
                                <th>STT</th>
                                <th>Tên sinh viên</th>
                                <th>Lớp</th>
                                <th>Tiêu đề</th>
                                <th>Ngày gửi</th>
                                <th>Tác vụ</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="myModal" role="dialog">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Phản hồi ý kiến</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span
                                    aria-hidden="true">×</span></button>
                    </div>
                    <div class="modal-body">
                        <form id="reply-form">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="title">Tiêu đề</label>
                                        <p><b class="title"></b></p>
                                    </div>
                                    <div class="form-group">
                                        <label for="content">Nội dung</label>
                                        <p><b class="content"></b></p>
                                    </div>
                                    <div class="form-group">
                                        <label for="email_content">Nội dung</label>
                                        <input type="hidden" name="id" class="id">
                                        <textarea class="form-control email_content" name="email_content"
                                                  id="email_content" cols="30" rows="10"></textarea>
                                        <p style="color:red; display: none;" class="name"></p>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <div class="modal-footer">
                            <button data-link="#" class="btn btn-primary" id="btn-reply" name="btn-reply" type="button">
                                Gửi
                            </button>
                            <button class="btn btn-secondary" type="button" data-dismiss="modal">Đóng</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="modalViewComment" role="dialog">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Xem chi tiết ý kiến</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span
                                    aria-hidden="true">×</span></button>
                    </div>
                    <div class="modal-body">
                        <h5>Tiêu đề</h5>
                        <p class="title"></p>
                        <h5>Nội dung ý kiến</h5>
                        <p class="content"></p>
                        <h5>Nội dung ý kiến đã phản hồi</h5>
                        <p class="reply"></p>
                    </div>
                </div>
            </div>
        </div>
    </main>

@endsection

@section('sub-javascript')
    <script>
        var url = "{{ route('ajax-get-comments') }}";
        var oTable = $('#commentsTable').DataTable({
            "processing": true,
            "serverSide": true,
            "autoWidth": false,
            "ajax": {
                "url": url,
                "dataType": "json",
                "type": "POST",
                "data": {
                    "_token": "{{ csrf_token() }}"
                }
            },
            "columns": [
                {data: "id", name: "comments.id"},
                {data: "userName", name: "users.name"},
                {data: "className", name: "classes.name"},
                {data: "title", name: "comments.title"},
                {data: "created_at", name: "comments.created_at"},
                {data: 'action', name: 'action', orderable: false, searchable: false}
            ],
            "language": {
                "lengthMenu": "Hiển thị _MENU_ bản ghi mỗi trang",
                "zeroRecords": "Không có bản ghi nào!",
                "info": "Hiển thị trang _PAGE_ của _PAGES_",
                "infoEmpty": "Không có bản ghi nào!!!",
                "infoFiltered": "(Đã lọc từ _MAX_ total bản ghi)",
                processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Tải dữ liệu...</span>'
            },
            "pageLength": 25
        });
    </script>
    <script src="{{ asset('js/web/comment/comment.js') }}"></script>

@endsection
