@extends('layouts.default')
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
                        <table class="table table-hover table-bordered" id="sampleTable">
                            <thead>
                            <tr>
                                <th>Lớp</th>
                                <th>Số lượng sinh viên</th>
                                <th></th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($faculty->classes as $class)
                                <tr>
                                    <td><a href="{{ route('class-detail',$class->id) }}">{{ $class->name }} </a></td>
                                    <td>{{ count($class->Students) }}</td>
                                    <td>
                                        <a data-id="{{$class->id}}" id="class-edit"
                                           data-edit-link="{{route('class-edit',$class->id)}}"
                                           data-update-link="{{route('class-update',$class->id)}}">
                                            <i class="fa fa-lg fa-edit " aria-hidden="true"> </i>
                                        </a>
                                    </td>
                                    <td>
                                        @if(!count($class->Students)>0)
                                            <a data-id="{{$class->id}}" id="class-destroy"
                                               data-link="{{route('class-destroy',$class->id)}}">
                                                <i class="fa fa-lg fa-trash-o" aria-hidden="true"> </i>
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
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
    <script type="text/javascript" src="{{ asset('template/js/plugins/jquery.dataTables.min.js') }} "></script>
    <script type="text/javascript" src="{{ asset('template/js/plugins/dataTables.bootstrap.min.js') }}"></script>
    {{--<script type="text/javascript">$('#sampleTable').DataTable();</script>--}}
    <script type="text/javascript" src="{{ asset('template/js/plugins/bootstrap-notify.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('template/js/plugins/sweetalert.min.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            $('div.alert-success').delay(2000).slideUp();

            $("a#class-edit").click(function () {
                var urlEdit = $(this).attr('data-edit-link');
                var urlUpdate = $(this).attr('data-update-link');
                var id = $(this).attr('data-id');
                $('.form-group').find('span.messageErrors').remove();
                $.ajax({
                    type: "get",
                    url: urlEdit,
                    data: {id: id},
                    dataType: 'json',
                    success: function (result) {
                        if (result.status === true) {
                            if (result.classes !== undefined) {
                                $.each(result.classes, function (elementName, value) {
//                                    $.each(arrMessagesEveryElement, function (messageType, messageValue) {
//                                    alert(elementName + "+ " + value)
                                    $('.' + elementName).val(value);
//                                    });
                                });
                            }
                        }
                    }
                });
                $('#myModal').find(".modal-title").text('Sửa thông tin khoa');
                $('#myModal').find(".modal-footer > button[name=btn-save-class]").html('Sửa');
                $('#myModal').find(".modal-footer > button[name=btn-save-class]").attr('data-link', urlUpdate);
                $('#myModal').modal('show');
            });

            $("#btn-save-class").click(function () {
//                $('#myModal').find(".modal-title").text('Thêm mới Khoa');
//                $('#myModal').find(".modal-footer > button[name=btn-save-faculty]").html('Thêm');
                var valueForm = $('form#class-form').serialize();
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
                                        $('form#class-form').find('.' + elementName).parents('.form-group ').append('<span class="messageErrors" style="color:red">' + messageValue + '</span>');
                                    });
                                });
                            }
                        } else if (result.status === true) {
                            $('#myModal').find('.modal-body').html('<p>Đã thêm lớp thành công</p>');
                            $("#myModal").find('.modal-footer').html('<button  class="btn btn-default" data-dismiss="modal">Đóng</button>');
                            $('#myModal').on('hidden.bs.modal', function (e) {
                                $('#myModal').find("input[type=text],input[type=number], select").val('');
                                $('.text-red').html('');
                                $('span.messageErrors').remove();
                                $('#myModal').find(".modal-title").text("Thêm mới lớp thuộc khoa {{ $faculty->name }}");
                                $('#myModal').find(".modal-footer > button[name=btn-save-class]").html('Thêm');
                                $('#myModal').find(".modal-footer > button[name=btn-save-class]").attr('data-link', "{{ route('class-store') }}");
                                location.reload();
                            });
                        }
                    }
                });
            });

            $('a#class-destroy').click(function () {
                var id = $(this).attr("data-id");
                var url = $(this).attr('data-link');
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
                                    swal("Deleted!", "Đã xóa lớp " + data.class.name, "success");
                                    $('.sa-confirm-button-container').click(function () {
                                        location.reload();
                                    })
                                } else {
                                    swal("Cancelled", "Không tìm thấy lớp !!! :)", "error");
                                }
                            }
                        });
                    } else {
                        swal("Đã hủy", "Đã hủy xóa lớp:)", "error");
                    }
                });
            });

            $('#myModal').on('hidden.bs.modal', function (e) {
                $('#myModal').find("input[type=text],input[type=number], select").val('');
                $('.text-red').html('');
                $('span.messageErrors').remove();
                $('#myModal').find(".modal-title").text("Thêm mới lớp thuộc khoa {{ $faculty->name }}");
                $('#myModal').find(".modal-footer > button[name=btn-save-class]").html('Thêm');
                $('#myModal').find(".modal-footer > button[name=btn-save-class]").attr('data-link', "{{ route('class-store') }}");
            });
        });

    </script>
@endsection