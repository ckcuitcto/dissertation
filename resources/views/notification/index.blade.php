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
                    <table class="table table-hover" id="sampleTable">
                        <thead>
                        <tr>
                            <th scope="col">STT</th>
                            <th scope="col">Tiêu đề</th>
                            {{--<th scope="col">Nội dung</th>--}}
                            <th scope="col">Người tạo</th>
                            <th scope="col">Ngày tạo</th>
                            <th>Tác vụ</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($notificationList as $key => $value)
                        <tr>
                            <th scope="row">{{ $key + 1 }}</th>
                            <td>{!! $value->title !!}</td>
                            {{--<td>{!! $value->content !!}</td>--}}
                            <td>{{ $value->name }}</td>
                            <td>{{ date('H:i d/m/y',strtotime($value->created_at)) }}</td>
                            <td>
                                <button class="view-notification btn btn-info" data-id="{{$value->id}}" link-view="{{route('notifications-show',$value->id)}}"><i class="fa fa-eye" aria-hidden="true"></i></button>
                            </td>
                        </tr>
                        @endforeach
                        </tbody>
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
        $(document).ready(function () {
            $('body').on('click', 'button.view-notification', function (e) {
                var url = $(this).attr('link-view');
                var id = $(this).attr('data-id');
                $.ajax({
                    type: "get",
                    url: url,
                    data: {id: id},
                    dataType: 'json',
                    success: function (result) {
                        if (result.status === true) {
                            if (result.notification !== undefined) {
                                $.each(result.notification, function (elementName, value) {
                                    $("div#myModal").find('p.' + elementName).append(value);
                                });
                            }
                        }
                    }
                });
                $('#myModal').modal('show');
            });

            $('div#myModal').on('hidden.bs.modal', function (e) {
                $('div#myModal').find("p").html('');
            });
        });
    </script>
@endsection