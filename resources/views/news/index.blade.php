@extends('layouts.default')

@section('content')
    <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class="fa fa-laptop"></i> Quản lý tin tức, sự kiện</h1>
                <p>Trường Đại học Công nghệ Sài Gòn</p>
            </div>
            <ul class="app-breadcrumb breadcrumb">
                <li class="breadcrumb-item"><a href="{{route('home')}}"><i class="fa fa-home fa-lg"></i></a></li>
                <li class="breadcrumb-item"><a href="#"> Quản lý tin tức, sự kiện</a></li>
            </ul>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="tile">
                    <table class="table table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>STT</th>
                            <th>Tiêu đề</th>
                            <th>Ngày tạo</th>
                            <th>Ngày cập nhật</th>
                            <th>Tác vụ</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($newsList as $key =>  $tintuc)
                            <tr>
                                <th>{{ $key + 1 }}</th>
                                <td>{{ $tintuc->id ." | ".$tintuc->title}}</td>
                                <td>{{$tintuc->created_at}}</td>
                                <td>{{$tintuc->updated_at}}</td>
                                <td>
                                    <a class="btn btn-primary" href="{{route('news-edit',$tintuc->id)}}">
                                        <i class="fa fa-lg fa-edit"></i>
                                    </a>

                                    <button type="button" class="btn btn-danger"
                                            data-news-id="{{$tintuc->id}}" id="news-destroy"
                                            data-news-link="{{route('news-destroy',$tintuc->id)}}"><i
                                                class="fa fa-lg fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <a href="{{ route('news-create') }}" class="btn btn-primary" id="btnAddNews" type="button">Thêm </a>
                </div>
            </div>
        </div>
    </main>
@endsection

@section('sub-javascript')
    <script type="text/javascript" src="{{ asset('template/js/plugins/jquery.dataTables.min.js') }} "></script>
    <script type="text/javascript" src="{{ asset('template/js/plugins/dataTables.bootstrap.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('template/js/plugins/bootstrap-notify.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('template/js/plugins/sweetalert.min.js') }}"></script>
    {{--<script type="text/javascript">$('#sampleTable').DataTable();</script>--}}

    <script>
        $(document).ready(function () {
            $('button#news-destroy').click(function () {
                var id = $(this).attr("data-news-id");
                var url = $(this).attr('data-news-link');
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
                                    swal("Deleted!", "Đã tin tức góp ý !", "success");
                                    $('.sa-confirm-button-container').click(function () {
                                        location.reload();
                                    })
                                } else {
                                    swal("Cancelled", "Không tìm thấy tin tức !!! :)", "error");
                                }
                            }
                        });
                    } else {
                        swal("Đã hủy", "Đã hủy xóa:)", "error");
                    }
                });
            });
        });
    </script>
@endsection