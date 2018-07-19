@extends('layouts.default')

@section('title')
    STU| Xuất File
@endsection

@section('content')
    <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class="fa fa-file-text-o"></i> Xuất file</h1>
                <p>Trường Đại học Công nghệ Sài Gòn</p>
            </div>
            <ul class="app-breadcrumb breadcrumb side">
                <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
                <li class="breadcrumb-item">Trang chủ</li>
                <li class="breadcrumb-item active"><a href="{{route('export-file-list')}}"> Xuất file </a></li>
            </ul>
        </div>
        <div class="row">
            <div class="col-md-12 custom-quanly-taikhoan">
                <div class="tile">
                    <div class="tile-body">
                        <form class="row" role="form" id="search-form" method="post">
                            {!! csrf_field() !!}
                            <div class="form-group col-md-3">
                                <label class="control-label">Học kì</label>
                                <select class="form-control semester_id" name="semester_id" id="semester_id">
                                    @foreach($semesters as $value)
                                        <option {{ ($value['id'] == $currentSemester->id )? "selected" : "" }} value="{{ $value['id'] }}">{{ $value['value']}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-3">
                                <label class="control-label">Khoa</label>
                                <select class="form-control faculty_id" name="faculty_id" id="faculty_id">
                                    @foreach($faculties as $value)
                                        <option value="{{ $value['id'] }}">{{ $value['name']}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-3 align-self-end">
                                <button class="btn btn-primary" type="submit"><i class="fa fa-fw fa-lg fa-search"></i>Tìm kiếm</button>
                            </div>
                        </form>
                    </div>
                    <div class="tile-body">
                        <form id="class-form-export" action="{{route('export-file')}}" method="post">
                            {{ csrf_field() }}
                            <table class="table table-hover table-bordered" id="exportTable">
                                <thead>
                                <tr>
                                    <th>Lớp</th>
                                    <th>Số lượng sinh viên</th>
                                    <th class="nosort">
                                        <div class="animated-checkbox">
                                            <label>
                                                <input type="checkbox" name="checkAll"><span class="label-text">Xuất file đánh giá điểm rèn luyện</span>
                                            </label>
                                        </div>
                                    </th>
                                </tr>
                                </thead>
                                    <tfoot>
                                        <th></th>
                                    </tfoot>
                            </table>
                            <input type="hidden" name="semesterChoose" id="semesterChoose" value="{{$currentSemester->id}}">
                            <input type="hidden" name="withDiscipline" value="no" id="withDiscipline">
                        </form>
                        <div class="row">
                            <div class="col-md-6">
                                <button class="btn btn-info" id="btnExport" type="button" data-link="{{route('export-file')}}">
                                    <i class="fa fa-file-excel-o" aria-hidden="true"></i>
                                    Xuất file đánh giá chưa áp dụng kỷ luật
                                </button>
                                <button class="btn btn-info" id="btnExportWithDiscipline" type="button" data-link="{{route('export-file')}}">
                                    <i class="fa fa-file-excel-o" aria-hidden="true"></i>
                                    Xuất file đánh giá đã áp dụng kỷ luật
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

@endsection

@section('sub-javascript')
    <script type="text/javascript">

            var oTable = $('#exportTable').DataTable({
                "processing": true,
                "serverSide": true,
                "ajax": {
                    "url": "{{ route('ajax-get-class-export') }}",
                    "dataType": "json",
                    "type": "POST",
                    "data": function (d) {
                        d.faculty_id = $('select[name=faculty_id]').val();
                        d.semester_id = $('select[name=semester_id]').val();
                        d._token = "{{ csrf_token() }}";
                    },
                },
                "columns": [
                    {data: "name", name: "classes.name"},
                    {data: "count", name: "count",searchable: false},
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                ],
                initComplete: function () {
                    this.api().columns().every(function () {
                        var column = this;
                        var input = document.createElement("input");
                        $(input).appendTo($(column.footer()).empty())
                            .on('change', function () {
                                var val = $.fn.dataTable.util.escapeRegex($(this).val());
                                column.search(val ? val : '', true, false).draw();
                            });
                    });
                },
                "language": {
                    "lengthMenu": "Hiển thị _MENU_ bản ghi mỗi trang",
                    "zeroRecords": "Không có bản ghi nào!",
                    "info": "Hiển thị trang _PAGE_ của _PAGES_",
                    "infoEmpty": "Không có bản ghi nào!!!",
                    "infoFiltered": "(Đã lọc từ _MAX_ total bản ghi)",
                    processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Tải dữ liệu...</span>'
                },
                "pageLength": 10,
            });
    </script>
    <script src="{{asset('js/web/export/index.js')}}"></script>
@endsection