@extends('layouts.default')
@section('content')
    <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class="fa fa-file-text-o"></i> Danh sách các quyền</h1>
                <p>Trường Đại học Công nghệ Sài Gòn</p>
            </div>
            <ul class="app-breadcrumb breadcrumb side">
                <li class="breadcrumb-item"><a href="{{'home'}}"><i class="fa fa-home fa-lg"></i></a></li>
                <li class="breadcrumb-item active"> Danh sách quyền</li>
            </ul>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="tile">

                    <div class="tile-body">
                        <table class="table table-hover table-bordered" id="permissionTable">
                            <thead>
                            <tr>
                                <th>Id</th>
                                <th>Tên</th>
                                <th>Tên hiển thị</th>
                                <th>Miêu tả</th>
                                <th>Tác vụ</th>
                            </tr>
                            </thead>
                        </table>
                        <div class="row">
                            <div class="col-md-6">
                                <button data-toggle="modal" data-target="#myModal" class="btn btn-primary"
                                        id="btnAddpermission" type="button"><i class="fa fa-pencil-square-o"
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
                        <h5 class="modal-title">Thêm mới quyền</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span
                                    aria-hidden="true">×</span></button>
                    </div>
                    <div class="modal-body">
                        <form id="permission-form">
                            {!! csrf_field() !!}
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="name">Quyền :</label>
                                        <input type="hidden" name="id" class="id" id="idpermissionModal">
                                        <input class="form-control name" id="name" name="name" type="text" required
                                               aria-describedby="permission">
                                        <p style="color:red; display: none;" class="name"></p>
                                    </div>
                                    <div class="form-group">
                                        <label for="name">Tên hiển thị:</label>
                                        <input class="form-control display_name" id="display_name" name="display_name" type="text" required
                                               aria-describedby="permission">
                                        <p style="color:red; display: none;" class="display_name"></p>
                                    </div>
                                    <div class="form-group">
                                        <label for="name">Miêu tả :</label>
                                        <input class="form-control description" id="description" name="description" type="text" required
                                               aria-describedby="description">
                                        <p style="color:red; display: none;" class="description"></p>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <div class="modal-footer">
                            <button data-link="{{ route('permission-store') }}" class="btn btn-primary"
                                    id="btn-save-permission" name="btn-save-permission" type="button">
                                Thêm
                            </button>
                            <button class="btn btn-secondary" type="button" data-dismiss="modal">Đóng</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

@endsection

@section('sub-javascript')
    <script>
            var oTable = $('#permissionTable').DataTable({
                "processing": true,
                "serverSide": true,
                "autoWidth": false,
                "ajax": {
                    "url": "{{ route('ajax-get-permissions') }}",
                    "dataType": "json",
                    "type": "POST",
                    "data": {
                        "_token": "{{ csrf_token() }}"
                    }
                },
                "columns": [
                    {data: "id", name: "id"},
                    {data: "name", name: "name"},
                    {data: "display_name", name: "display_name"},
                    {data: "description", name: "description"},
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
    <script src="{{ asset('js/web/permission/index.js') }}"></script>
@endsection
