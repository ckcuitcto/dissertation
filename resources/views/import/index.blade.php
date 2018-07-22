@extends('layouts.default')

@section('content')
    <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class="fa fa-laptop"></i> Danh sách file đã nhập</h1>
                <p>Trường Đại học Công nghệ Sài Gòn</p>
            </div>
            <ul class="app-breadcrumb breadcrumb">
                <li class="breadcrumb-item"><a href="{{route('home')}}"><i class="fa fa-home fa-lg"></i></a></li>
                <li class="breadcrumb-item"> Danh sách file</li>
            </ul>
        </div>
        <div class="row">
            <div class="col-md-12 custom-quanly-taikhoan">
                <div class="tile">
                    <table id="filesTable" class="table table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tên file</th>
                            <th>Người nhập</th>
                            <th>Trạng thái</th>
                            <th>Tải file</th>
                        </tr>
                        </thead>
                        {{--<tbody>--}}
                        {{--@foreach($imports as $key => $value)--}}
                            {{--<tr>--}}
                                {{--<th>{{  $value->id }}</th>--}}
                                {{--<td>--}}
                                    {{--@if(file_exists(STUDENT_PATH_STORE.$value->file_path))--}}
                                        {{--<a href="{{ asset(STUDENT_PATH_STORE.$value->file_path) }}"> {{$value->file_name}}</a>--}}
                                    {{--@elseif(file_exists(STUDENT_LIST_EACH_SEMESTER_PATH.$value->file_path))--}}
                                        {{--<a href="{{ asset(STUDENT_LIST_EACH_SEMESTER_PATH.$value->file_path) }}"> {{$value->file_name}}</a>--}}
                                    {{--@else--}}
                                        {{--{{ $value->file_name }}--}}
                                    {{--@endif--}}
                                {{--</td>--}}
                                {{--<td>{{ $value->Staff->User->name }}</td>--}}
                                {{--<td>{{ $value->status }}</td>--}}

                            {{--</tr>--}}
                        {{--@endforeach--}}
                        {{--</tbody>--}}
                    </table>
                </div>
            </div>
        </div>
    </main>
@endsection

@section('sub-javascript')
    <script>
        var oTable = $('#filesTable').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                // copy ttên route. qua bên web.php copy thêm 1 cái r đổi tên
                "url": "{{ route('ajax-get-files') }}",
                "dataType": "json",
                "type": "POST",
                "data": {
                    "_token": "{{ csrf_token() }}"
                }
            },
            "columns": [
                {data: "id", name: "file_imports.id"},
                {data: "file_name", name: "file_imports.file_name"},
                {data: "name", name: "users.name"},
                {data: "status", name: "file_imports.status"},
                // {data: "valid", name: "proofs.valid"},
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
    </script>
@endsection