@extends('layouts.default')

@section('title')
    STU| Thong Tin Khoa {{ $faculty->name }}
@endsection

@section('content')
    <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class="fa fa-file-text-o"></i> Danh sách các lớp thuộc khoa {{ $faculty->name }}</h1>
                <p>Trường Đại học Công nghệ Sài Gòn</p>
            </div>
            <ul class="app-breadcrumb breadcrumb side">
                <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
                <li class="breadcrumb-item">Trang chủ</li>
                <li class="breadcrumb-item active"><a href="#"> Khoa {{ $faculty->name }} </a></li>
            </ul>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="tile">
                    <div class="tile faculty-setting">
                        <div id="faculty-info">
                            <h4 class="line-head">Thông tin khoa {{ $faculty->name }}</h4>
                            <div class="row">
                                <div class="col-md-10">
                                    <div class="fo"></div>
                                    <div>Số lớp : {{ count($faculty->Classes) }}</div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    @include('alert.success')
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tile-body">
                        <form id="class-form-export" action="{{route('export-file')}}" method="post">
                            {{ csrf_field() }}
                            <table class="table table-hover table-bordered" id="facultyDetailTable">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Lớp</th>
                                    <th>Số lượng sinh viên</th>
                                    <th>Tác vụ</th>
                                </tr>
                                </thead>
                            </table>
                        </form>
                        <div class="row">
                            <div class="col-md-6">
                                <button data-toggle="modal" data-target="#myModal" class="btn btn-primary"
                                        id="btn-add-class" type="button"><i class="fa fa-pencil-square-o"
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
                        <h5 class="modal-title">Thêm mới lớp thuộc khoa {{ $faculty->name }}</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span
                                    aria-hidden="true">×</span></button>
                    </div>
                    <div class="modal-body">

                        <form id="class-form">
                            {!! csrf_field() !!}
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="name">Tên lớp :</label>
                                        {{--<input type="hidden" name="id" class="id" id="modal-class-id">--}}
                                        <input type="hidden" name="faculty_id" class="faculty_id" id="faculty_id"
                                               value="{{ $faculty->id }}">
                                        <input class="form-control name" id="name" name="name" type="text" required
                                               aria-describedby="class">
                                        <p style="color:red; display: none;" class="name"></p>
                                    </div>
                                    <div class="form-group">
                                        <label for="staff_id">Cố vấn học tập :</label>
                                        <select class="form-control staff_id" id="staff_id" name="staff_id" type="text"
                                                required aria-describedby="staff">
                                            @if($faculty->Users->where('role_id','=', ROLE_COVANHOCTAP))
                                                @foreach($faculty->Users->where('role_id','>','2') as $value)
                                                    {{--                                                    @php var_dump($value->Staff); @endphp--}}
                                                    <option value="{{ $value->Staff->id }}"> {{ $value->Staff->id . "|". $value->name }}  </option>
                                                @endforeach
                                            @else
                                                <option> Không có giảng viên nào !</option>
                                            @endif

                                        </select>
                                        <p style="color:red; display: none;" class="staff_id"></p>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <div class="modal-footer">
                            <button data-link="{{ route('class-store') }}" class="btn btn-primary"
                                    id="btn-save-class" name="btn-save-class" type="button">
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
        var oTable = $('#facultyDetailTable').DataTable({
            "processing": true,
            "serverSide": true,
            "autoWidth": false,
            "ajax": {
                "url": "{{ route('ajax-get-faculty-detail') }}",
                "dataType": "json",
                "type": "POST",
                "data": {
                    "_token": "{{ csrf_token() }}"
                }
            },
            "columns": [
                {data: "id", name: "id"},
                {data: "name", name: "name"},
                {data: "countStudent", name: "countStudent", searchable: false},
                {data: 'action', name: 'action', orderable: false, searchable: false}
            ],
            "language": {
                "lengthMenu": "Hiển thị _MENU_ bản ghi mỗi trang",
                // "zeroRecords": "Không có bản ghi nào!",
                // "info": "Hiển thị trang _PAGE_ của _PAGES_",
                "infoEmpty": "Không có bản ghi nào!!!",
                "infoFiltered": "(Đã lọc từ _MAX_ total bản ghi)"
            },
            "pageLength": 25
        });
    </script>
    <script type="text/javascript" src="{{ asset('js/web/faculty-detail.js') }} ">  </script>
@endsection