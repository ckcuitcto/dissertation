@extends('layouts.default')

@section('content')
<main class="app-content">
<div class="app-title">
        <div>
          <h1><i class="fa fa-laptop"></i> Quản lý tin tức, sự kiện</h1>
          <p>Trường Đại học Công nghệ Sài Gòn</p>
        </div>
        <ul class="app-breadcrumb breadcrumb">
          <li class="breadcrumb-item"><a href="#"><i class="fa fa-home fa-lg"></i></a></li>
          <li class="breadcrumb-item"><a href="#"> Quản lý tin tức, sự kiện</a></li>
        </ul>
      </div>
        <div class="row">
          <div class="col-md-12">
            <div class="tile">
              <table class="table">
                <thead>
                  <tr>
                    <th scope="col">STT</th>
                    <th scope="col">Tiêu đề</th>
                    <th scope="col">Nội dung</th>
                    <th scope="col">Ngày tạo</th>
                    <th scope="col">Ngày cập nhật</th>                    
                    <th>Tác vụ</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($newsList as $tintuc)
                  <tr>
                    <th scope="row">1</th>
                    <td>{{$tintuc->title}}</td>
                    <td>{{$tintuc->content}}</td>
                    <td>{{$tintuc->created_at}}</td>
                    <td>{{$tintuc->updated_at}}</td>
                    <td>                        
                        <button type="button" class="btn btn-secondary">
                            <a data-news-id="{{$tintuc->id}}" id="news-update"
                                data-news-edit-link="{{route('news-edit',$tintuc->id)}}"
                                data-news-update-link="{{route('news-update',$tintuc->id)}}">
                             </a>Sửa</button>
                              
                        <button type="button" class="btn btn-danger">
                          <a data-news-id="{{$tintuc->id}}" id="news-destroy"
                            data-news-link="{{route('news-destroy',$tintuc->id)}}">Xóa
                         </a>
                        </button>
                      </td> 
                  </tr>                 
                  @endforeach
                </tbody>
              </table>
              <button type="button" class="btn btn-primary">Thêm</button>
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
          $("a#news-update").click(function () {
                var urlEdit = $(this).attr('data-news-edit-link');
                var urlUpdate = $(this).attr('data-news-update-link');
                var id = $(this).attr('data-news-id');
                $('.form-group').find('span.messageErrors').remove();
                $.ajax({
                    type: "get",
                    url: urlEdit,
                    data: {id: id},
                    dataType: 'json',
                    success: function (result) {
                        if (result.status === true) {
                            if (result.news !== undefined) {
                                $.each(result.news, function (elementName, value) {
//                                    $.each(arrMessagesEveryElement, function (messageType, messageValue) {
//                                    alert(elementName + "+ " + messageValue)
                                    $('.' + elementName).val(value);
//                                    });
                                });
                            }
                        }
                    }
                });
                $('#myModal').find(".modal-title").text('Sửa thông tin khoa');
                $('#myModal').find(".modal-footer > button[name=btn-save-news]").html('Sửa')
                $('#myModal').find(".modal-footer > button[name=btn-save-news]").attr('data-link',urlUpdate);
                $('#myModal').modal('show');
            });

            $('a#news-destroy').click(function () {
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

            CKEDITOR.replace('email_content');
        });

    </script>

@endsection