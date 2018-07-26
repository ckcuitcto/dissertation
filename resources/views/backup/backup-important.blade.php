@extends('layouts.default')
@section('content')
    <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class="fa fa-file-text-o"></i> BACKUP </h1>
                <p>Trường Đại học Công nghệ Sài Gòn</p>
            </div>
            <ul class="app-breadcrumb breadcrumb">
                <li class="breadcrumb-item"><a href="{{route('home')}}"><i class="fa fa-home fa-lg"></i></a></li>
                <li class="breadcrumb-item active"> BACKUP</li>
            </ul>
        </div>
        <div class="row">
            <div class="col-md-12 custom-quanly-taikhoan">
                <div class="tile">
                    <div class="tile-title">
                        Lưu ý: Chức năng này dùng để backup lại cơ sở dữ liệu của ứng dụng.<br> Khi chạy, ứng dụng sẽ lưu lại Database hiện tại của hệ thống. Sau đó sẽ xóa các học kì đã chọn và TOÀN BỘ DỮ LIỆU LIÊN QUAN đến học kì đó
                    </div>
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
                "url": "{{ route('ajax-get-semester-for-backup-important') }}",
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

        $('body').on('click', 'a.destroy-semester', function (e) {
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
                        beforeSend: function(){
                            $("#ajax_loader").show();
                        },
                        success: function (data) {
                            $("#ajax_loader").hide();
                            if (data.status === true) {
                                swal("Deleted! ", "Đã xóa học kì " + data.semester.term + " năm học " + data.semester.year_from + "-" + data.semester.year_to, "success");
                                if(data.file_path !== undefined){
                                    var a = document.createElement('a');
                                    a.href = data.file_path;
                                    a.download = data.file_name;
                                    a.click();
                                }
                                $('.sa-confirm-button-container').click(function () {
                                    oTable.draw();
                                })
                            } else {
                                swal("Không thành công", data.message +" !!! :)", "error");
                            }
                        }
                    });
                } else {
                    swal("Đã hủy", "Đã hủy xóa học kì:)", "error");
                }
            });
        });
    </script>
@endsection


