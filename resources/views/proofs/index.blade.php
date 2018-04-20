@extends('layouts.default')

@section('content')
<main class="app-content">
    <div class="app-title">
      <div>
        <h1><i class="fa fa-calendar-o"></i> Quản lý minh chứng</h1>
        <p>Trường Đại học Công nghệ Sài Gòn</p>
      </div>
      <ul class="app-breadcrumb breadcrumb">
        <li class="breadcrumb-item"><a href="#"><i class="fa fa-home fa-lg"></i></a></li>
        <li class="breadcrumb-item"><a href="#"> Quản lý minh chứng</a></li>
      </ul>
    </div>
    <div class="row">
      <div class="col-md-12">
        <div class="tile">
          <section class="invoice">     
              <div class="tile-body">
                <table class="table table-hover table-bordered" id="sampleTable">
                  <thead>
                    <tr>
                      <th>STT</th>
                      <th>Tên sinh viên</th>
                      <th>Lớp</th>
                      <th>Học kỳ</th>
                      <th>Năm học</th>
                      <th>Trạng thái</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>hh</td>
                      <td>hh</td>
                      <td>hh</td>
                      <td>hh</td>
                      <td>hh</td>
                      <td>
                          <button data-toggle="modal" data-target="#myModal" class="btn btn-primary"
                          id="btnAddFaculty" type="button"><i class="fa fa-pencil-square-o"
                                                              aria-hidden="true"></i>Sửa</button>
                           <button class="btn btn-danger" id="demoSwal" type="button">Xóa</button>
                      </td>
                    </tr>
                    <tr>
                      <td>hh</td>
                      <td>hh</td>
                      <td>hh</td>
                      <td>hh</td>
                      <td>hh</td>
                      <td>
                          <button data-toggle="modal" data-target="#myModal" class="btn btn-primary"
                          id="btnAddFaculty" type="button"><i class="fa fa-pencil-square-o"
                                                              aria-hidden="true"></i>Sửa</button>
                        <button class="btn btn-danger" id="demoSwal" type="button">Xóa</button>
                      </td>
                    </tr>
                    <tr>
                      <td>hh</td>
                      <td>hh</td>
                      <td>hh</td>
                      <td>hh</td>
                      <td>hh</td>
                      <td>
                          <button data-toggle="modal" data-target="#myModal" class="btn btn-primary"
                          id="btnAddFaculty" type="button"><i class="fa fa-pencil-square-o"
                                                              aria-hidden="true"></i>Sửa</button>
                            <button class="btn btn-danger" id="demoSwal" type="button">Xóa</button>
                      </td>
                    </tr>                              
                    <tr>
                      <td>hh</td>
                      <td>hh</td>
                      <td>hh</td>
                      <td>hh</td>
                      <td>hh</td>
                      <td>
                          <button data-toggle="modal" data-target="#myModal" class="btn btn-primary"
                          id="btnAddFaculty" type="button"><i class="fa fa-pencil-square-o"  aria-hidden="true"></i>Sửa</button>
                            
                          <button class="btn btn-danger" id="demoSwal" type="button">Xóa</button>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
          </section>
        </div>
      </div>
    </div>
    <div class="modal fade" id="myModal" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Phiếu đánh giá điểm rèn luyện</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body">

                    <form id="faculty-form">
                        {!! csrf_field() !!}
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="name">Tên khoa :</label>
                                    <input type="hidden" name="id" class="id" id="idFacultyModal">
                                    <input class="form-control name" id="name" name="name" type="text" required
                                           aria-describedby="faculty" placeholder="Nhập tên khoa">
                                    <p style="color:red; display: none;" class="name"></p>
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="modal-footer">
                        <button data-link="#" class="btn btn-primary"
                                id="btn-save-faculty" name="btn-save-faculty" type="button">
                            Sửa
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
    <script type="text/javascript" src="{{ asset('template/js/plugins/jquery.dataTables.min.js') }} "></script>
    <script type="text/javascript" src="{{ asset('template/js/plugins/dataTables.bootstrap.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('template/js/plugins/bootstrap-notify.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('template/js/plugins/sweetalert.min.js') }}"></script>
    <script type="text/javascript">$('#sampleTable').DataTable();</script>


    <script>
        $(document).ready(function () {
            $("a#update-faculty").click(function () {
                var urlEdit = $(this).attr('data-faculty-edit-link');
                var urlUpdate = $(this).attr('data-faculty-update-link');
                var id = $(this).attr('data-faculty-id');
                $('.form-group').find('span.messageErrors').remove();
                $.ajax({
                    type: "get",
                    url: urlEdit,
                    data: {id: id},
                    dataType: 'json',
                    success: function (result) {
                        if (result.status === true) {
                            if (result.faculty !== undefined) {
                                $.each(result.faculty, function (elementName, value) {
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
                $('#myModal').find(".modal-footer > button[name=btn-save-faculty]").html('Sửa')
                $('#myModal').find(".modal-footer > button[name=btn-save-faculty]").attr('data-link',urlUpdate);
                $('#myModal').modal('show');
            });

            $("#btn-save-faculty").click(function () {
                var valueForm = $('form#faculty-form').serialize();
                var url = $(this).attr('data-link');
                $('.form-group').find('span.messageErrors').remove();
                $.ajax({
                    type: "post",
                    url: url,
                    data: valueForm,
                    dataType: 'json',
                    success: function (result) {
                        if (result.status === false) {
                            //show error list fields
                            if (result.arrMessages !== undefined) {
                                $.each(result.arrMessages, function (elementName, arrMessagesEveryElement) {
                                    $.each(arrMessagesEveryElement, function (messageType, messageValue) {
                                        $('form#faculty-form').find('.' + elementName).parents('.form-group ').append('<span class="messageErrors" style="color:red">' + messageValue + '</span>');
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

            $('a#destroy-faculty').click(function () {
                var id = $(this).attr("data-faculty-id");
                var url = $(this).attr('data-faculty-link');
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
                                    swal("Deleted!", "Đã xóa Khoa " + data.faculty.name, "success");
                                    $('.sa-confirm-button-container').click(function () {
                                        location.reload();
                                    })
                                } else {
                                    swal("Cancelled", "Không tìm thấy Khoa !!! :)", "error");
                                }
                            }
                        });
                    } else {
                        swal("Đã hủy", "Đã hủy xóa khoa:)", "error");
                    }
                });
            });

        });
    </script>

<script type="text/javascript">  
  $('#demoSwal').click(function(){
      swal({
          title: "Bạn có chắc muốn xóa?",
          text: "Bạn sẽ không khôi phục được hành động này.",
          type: "warning",
          showCancelButton: true,
          confirmButtonText: "Yes!",
          cancelButtonText: "No!",
          closeOnConfirm: false,
          closeOnCancel: false
      }, function(isConfirm) {
          if (isConfirm) {
              swal("Deleted!", "Đã xóa sinh viên.", "success");
          } else {
              swal("Cancelled", "Mọi thứ an toàn :)", "error");
          }
      });
  });
</script>
@endsection