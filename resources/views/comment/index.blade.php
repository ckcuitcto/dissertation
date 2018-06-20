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
                        <table class="table table-hover table-bordered" id="sampleTable">
                            {{--@if($user->Role->id <= 3)--}}
                            <thead>
                            <tr>
                                <th>STT</th>
                                {{--<th>Tên sinh viên</th>--}}
                                <th>Lớp</th>
                                <th>Tiêu đề</th>
                                {{-- <th>Nội dung ý kiến</th> --}}
                                <th>Ngày gửi</th>
                                <th>Tác vụ</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($comments as $cmt)
                                <tr>
                                    <td>{{ $cmt->id }}</td>
                                    {{--                                    <td> {{ $cmt->userName}}</td>--}}
                                    <td>{{ $cmt->className }}</td>
                                    <td>{!! $cmt->title !!} </td>
                                    {{-- <td>{!! $cmt->content !!}</td> --}}
                                    <td>{{ $cmt->created_at }}</td>
                                    <td align="left">
                                    @can('comment-reply')
                                        @if(empty($cmt->reply))
                                        <a title="Phản hồi ý kiến" class="btn btn-primary" style="color:white" data-comment-id="{{$cmt->id}}" id="comment-reply"
                                           data-comment-show-link="{{route('comment-show',$cmt->id)}}"
                                           data-comment-reply-link="{{route('comment-reply',$cmt->id)}}">
                                            <i class="fa fa-lg fa-edit" aria-hidden="true"> </i>
                                        </a>
                                        @else
                                            <a title="Đã phản hồi ý kiến" class="btn btn-success" style="color:white" data-comment-id="{{$cmt->id}}" id="comment-reply"
                                               data-comment-show-link="{{route('comment-show',$cmt->id)}}"
                                               data-comment-reply-link="{{route('comment-reply',$cmt->id)}}">
                                                <i class="fa fa-lg fa-check" aria-hidden="true"> </i>
                                            </a>
                                        @endif
                                    @endcan
                                    @can('comment-delete')
                                        <a title="Xóa" class="btn btn-danger" style="color:white" data-comment-id="{{$cmt->id}}" id="comment-destroy"
                                           data-comment-link="{{route('comment-destroy',$cmt->id)}}">
                                            <i class="fa fa-lg fa-trash-o" aria-hidden="true"> </i>
                                        </a>
                                    @endcan
                                        <button class="view-comment btn btn-info"
                                                data-id="{{$cmt->id}}"
                                                link-view="{{route('comment-show',$cmt->id)}}">
                                            <i class="fa fa-eye" aria-hidden="true"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
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
                                        <textarea class="form-control email_content" name="email_content" id="email_content" cols="30" rows="10"></textarea>
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
        $(document).ready(function () {
            $('div.alert-success').delay(2000).slideUp();

            $("a#comment-reply").click(function () {
                var urlShow = $(this).attr('data-comment-show-link');
                var urlReply = $(this).attr('data-comment-reply-link');
                var id = $(this).attr('data-faculty-id');
                $('.form-group').find('span.messageErrors').remove();
                $.ajax({
                    type: "get",
                    url: urlShow,
                    data: {id: id},
                    dataType: 'json',
                    success: function (result) {
                        if (result.status === true) {
                            if (result.comment !== undefined) {
                                $.each(result.comment, function (elementName, value) {
//                                    $.each(arrMessagesEveryElement, function (messageType, messageValue) {
//                                    alert(elementName + "+ " + value);
                                    if (elementName === 'title' || elementName === 'content') {
                                        $('.' + elementName).html(value);
                                    } else if(elementName === 'reply') {
                                        $('.email_content').html(value);
                                    }else{
                                        $('.' + elementName).val(value);
                                    }
//                                    });
                                });
                            }
                        }
                    }
                });
                $('#myModal').find(".modal-footer > button[name=btn-reply]").attr('data-link', urlReply);
                $('#myModal').modal('show');
            });

            $("#btn-reply").click(function () {
                var valueForm = $('form#reply-form').serialize();
                var url = $(this).attr('data-link');
                $('.form-group').find('span.messageErrors').remove();
                $.ajax({
                    type: "post",
                    url: url,
                    data: valueForm,
                    dataType: 'json',
                    cache: false,
                    success: function (result) {
                        if (result.status === false) {
                            //show error list fields
                            if (result.arrMessages !== undefined) {
                                $.each(result.arrMessages, function (elementName, arrMessagesEveryElement) {
                                    $.each(arrMessagesEveryElement, function (messageType, messageValue) {
                                        $('form#reply-form').find('.' + elementName).parents('.form-group ').append('<span class="messageErrors" style="color:red">' + messageValue + '</span>');
                                    });
                                });
                            }
                        } else if (result.status === true) {
                            $('#myModal').find('.modal-body').html('<p>Thành công</p>');
                            $("#myModal").find('.modal-footer').html('<button  class="btn btn-default" data-dismiss="modal">Đóng</button>');
                           $('#myModal').on('hidden.bs.modal', function (e) {
                               location.reload();
                           });
                        }
                    }
                });
            });

            $('a#comment-destroy').click(function () {
                var id = $(this).attr("data-comment-id");
                var url = $(this).attr('data-comment-link');
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
                                    swal("Deleted!", "Đã xóa góp ý !", "success");
                                    $('.sa-confirm-button-container').click(function () {
                                        location.reload();
                                    })
                                } else {
                                    swal("Cancelled", "Không tìm thấy góp ý !!! :)", "error");
                                }
                            }
                        });
                    } else {
                        swal("Đã hủy", "Đã hủy xóa:)", "error");
                    }
                });
            });

            $('body').on('click', 'button.view-comment', function (e) {
                var url = $(this).attr('link-view');
                var id = $(this).attr('data-id');
                $.ajax({
                    type: "get",
                    url: url,
                    data: {id: id},
                    dataType: 'json',
                    success: function (result) {
                        if (result.status === true) {
                            if (result.comment !== undefined) {
                                $.each(result.comment, function (elementName, value) {
                                    $("div#modalViewComment").find('p.' + elementName).append(value);
                                });
                            }
                        }
                    }
                });
                $('#modalViewComment').modal('show');
            });

            $('div#modalViewComment').on('hidden.bs.modal', function (e) {
                $('div#modalViewComment').find("p").html('');
            });

            $('#myModal').on('hidden.bs.modal', function (e) {
                $('#myModal').find("textarea.email_content").html('');
                $('.text-red').html('');
                $('span.messageErrors').remove();
            });
        });

    </script>

@endsection
