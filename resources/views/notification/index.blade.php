@extends('layouts.default')

@section('content')
    <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class="fa fa-laptop"></i> Quản lý thông báo</h1>
                <p>Trường Đại học Công nghệ Sài Gòn</p>
            </div>
            <ul class="app-breadcrumb breadcrumb">
                <li class="breadcrumb-item"><a href="#"><i class="fa fa-home fa-lg"></i></a></li>
                <li class="breadcrumb-item"><a href="#">Quản lý thông báo</a></li>
            </ul>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="tile">
                    <table class="table table-hover" id="notificationTable">
                        <thead>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Tiêu đề</th>
                            <th scope="col">Trạng thái</th>
                            <th scope="col">Người tạo</th>
                            <th scope="col">Ngày tạo</th>
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
                        <h5 class="modal-title">Xem chi tiết thông báo</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span
                                    aria-hidden="true">×</span></button>
                    </div>
                    <div class="modal-body">
                        <p class="title"></p>
                        <p class="content"></p>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

@section('sub-javascript')
    <script>
            var oTable = $('#notificationTable').DataTable({
                "processing": true,
                "serverSide": true,
                "autoWidth": false,
                "ajax": {
                    "url": "{{ route('ajax-get-notifications') }}",
                    "dataType": "json",
                    "type": "POST",
                    "data": {
                        "_token": "{{ csrf_token() }}"
                    }
                },
                "columns": [
                    {data: "id", name: "id"},
                    {data: "title", name: "title"},
                    {data: "status", name: "status"},
                    {data: "createdByName", name: "createdByName"},
                    {data: "created_at", name: "created_at"},
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

            @if(!empty($notification))
                $('input[type="search"]').val("{{ $notification->id.' '.$notification->title }}").keyup();
            @endif
    </script>
    <script src="{{ asset('js/web/notification/index.js') }}"></script>
@endsection