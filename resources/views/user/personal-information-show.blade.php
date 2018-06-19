@extends('layouts.default')

@section('title')
    STU| Thong Tin Ca Nhan {{ $user->name }}
@endsection
@section('content')
    <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class="fa fa-edit"></i> Thông tin cá nhân</h1>
                <p>Trường đại học Công nghệ Sài Gòn</p>
            </div>
            <ul class="app-breadcrumb breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home')}}"><i class="fa fa-home fa-lg"></i></a></li>
                <li class="breadcrumb-item">Thông tin cá nhân</li>
            </ul>
        </div>
        <form id="form-update-infomation" method="post" enctype="multipart/form-data">
        <div class="row user">

            <div class="col-md-3">
                <div class="tile p-0">
                    <div class="info">

                        @if(!empty($user->avatar))
                            <img id="imageChange" style="width: 100%;" class="user-image" src="{{ asset('image/avatar/'.$user->avatar ) }}" title="Click vào đây ảnh để đổi ảnh đại diện">
                        @else
                            <img id="imageChange" style="width: 100%;" class="user-image" src="{{ asset('image/avatar_default.jpg') }}" title="Click vào đây ảnh để đổi ảnh đại diện">
                        @endif
                        <input type='file' id="avatar" class="avatar can-edit" name="avatar" disabled style="display: none;"/>
                        <div class="alert alert-primary" role="alert">

                        {{--</div>--}}
                        {{--<div class="alert alert-success" role="alert">--}}
                         <p>  {{ (!empty($user->updated_at)) ? "Cập nhật gần nhất ".$user->updated_at  : "" }} </p>
                        {{--</div>--}}
                        {{--<div class="alert alert-info" role="alert">--}}
                            <p>Yêu cầu chỉnh sửa các thông tin khác xin liên hệ: Phòng đào tạo</p>
                            <p>- Trụ sở: 180 Cao Lỗ, Phường 4, Quận 8, Tp. Hồ Chí Minh</p>
                            <p>- ĐT: 028 3850 5520</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- <div class="tab-pane fade" id="user-settings"> -->
            <div class="tile user-settings col-md-9">
                <h4 class="line-head">Tổng quan</h4>

                    <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                    <input type="hidden" name="id" value="{{ $user->users_id }}">
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <label>MSSV</label>
                            <input class="form-control" id="" type="text"
                                    name="" value="{{ $user->users_id }}" disabled>
                        </div>

                        <div class="col-md-4">
                            <label>Họ và tên</label>
                            <input class="form-control name can-edit" id="name" type="text"  name="name"
                                   value="{{ $user->name }}" disabled>
                        </div>
                        <div class="col-md-4">
                            <label>Ngày sinh</label>
                            <input class="form-control birthday can-edit" id="birthday" type="text"
                                    name="birthday" value="{{ $user->birthday }}" disabled>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-4">
                            <label>Lớp</label>
                            <input class="form-control" type="text" value="{{$user->Student->Classes->name OR ""}}" disabled>
                        </div>
                        <div class="col-md-4">
                            <label>Khoa</label>
                            <input class="form-control" id="" type="text"
                                   name="" value="{{$user->Faculty->name OR ""}}" disabled>
                        </div>
                        <div class="col-md-4">
                            <label>Niên khóa</label>
                            <div class="row">
                                <div class="col-md-6">
                                    <input class="form-control" id="" type="text" value="{{$user->Student->academic_year_from OR ""}}" disabled>
                                </div>
                                <div class="col-md-6">
                                    <input class="form-control" id="" type="text" value="{{$user->Student->academic_year_to OR ""}}" disabled>
                                </div>
                            </div>

                        </div>
                    </div>

                    <hr>
                    <h4 class="line-head">Thông tin cơ bản - liên hệ</h4>

                    <div class="row mb-4">
                        <div class="col-md-4">
                            <label>Email</label>
                            <input class="form-control email can-edit" id="email" type="text" name="email" value="{{$user->email OR ""}}" disabled>
                        </div>

                        <div class="col-md-4">
                            <label>Điện thoại</label>
                            <input class="form-control phone_number can-edit" id="phone_number" type="text" name="phone_number" value="{{$user->phone_number OR ""}}" disabled>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Giới tính </label>
                                <div class="form-check">
                                    <label class="form-check-label">
                                        <input class="form-check-input can-edit gender" value="{{ MALE }}" disabled {{ ($user->gender == MALE) ? "checked" : "" }} }} type="radio" name="gender">Nam
                                    </label>
                                </div>
                                <div class="form-check">
                                    <label class="form-check-label">
                                        <input class="form-check-input can-edit gender" value="{{ FEMALE }}" disabled {{ ($user->gender == FEMALE) ? "checked" : "" }} type="radio" name="gender">Nữ
                                    </label>
                                </div>
                            </div>
                        </div>


                    </div>

                    <div class="row mb-4">
                        <div class="col-md-12">
                            <label>Địa chỉ</label>
                            <textarea class="form-control address can-edit" rows="3" id="address" name="address" value="{{$user->address OR ""}}" disabled >{!! $user->address !!}</textarea>
                            {{-- <input class="form-control address" id="address" type="text" name="address" value="{{$user->address OR ""}}" disabled> --}}
                        </div>
                    </div>

                    <div class="row mb-10">
                        <div class="col-md-12">
                            <div class="button-edit" style="display: none;">
                                <button class="btn btn-primary" data-link="{{ route('personal-information-update',$user->users_id) }}" id="btn-save-inform">
                                    <i class="fa fa-fw fa-lg fa-check-circle"></i>Lưu thông tin
                                </button>
                                &nbsp;&nbsp;&nbsp;
                                <a class="btn btn-secondary" id="btn-cancel-inform"><i class="fa fa-fw fa-lg fa-times-circle"></i>Hủy</a>
                            </div>

                        </div>
                    </div>
                <a class="btn btn-primary" id="btn-update-inform" style="color:white"><i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                    Sửa
                </a>
            </div>
            {{-- </div>          --}}
        </div>
        </form>
    </main>
@endsection

@section('sub-javascript')
    <script type="text/javascript" src=" {{ asset('template/js/plugins/bootstrap-notify.min.js') }}"></script>
    <script type="text/javascript" src=" {{ asset('template/js/plugins/sweetalert.min.js') }}"></script>
    <script>
        $(document).ready(function () {

            $('input#birthday').datepicker({
                todayBtn: "linked",
                language: "vi",
                format: "dd/mm/yyyy",
                clearBtn: true,
                orientation: "bot right",
                autoclose: true,
                toggleActive: true,
            });

            $("img#imageChange").click(function() {
                $("input#avatar").click();
            });

            $("a#btn-update-inform").click(function(){
                $(".button-edit").show();
                $(".can-edit").removeAttr('disabled');
                $("a#btn-update-inform").hide();
            });
            $("a#btn-cancel-inform").click(function(){
                $(".button-edit").hide();
                $(".can-edit").prop('disabled',true);
                $("a#btn-update-inform").show();
                $('span.messageErrors').remove();
            });


            $("form#form-update-infomation").submit(function (e) {
                e.preventDefault();
                var formData = new FormData(this);
                $('span.messageErrors').remove();

                $.ajax({
                    type: "post",
                    url: "{{ route('personal-information-update',$user->users_id) }}",
                    data: formData,
                    dataType: 'json',
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function (result) {
                        if (result.status === false) {
                            //show error list fields
                            if (result.arrMessages !== undefined) {
                                $.each(result.arrMessages, function (elementName, arrMessagesEveryElement) {
                                    $.each(arrMessagesEveryElement, function (messageType, messageValue) {
                                        if(elementName === 'address'){
                                            $('form#form-update-infomation').find('.' + elementName).parents('.col-md-12').append('<span class="messageErrors" style="color:red">' + messageValue + '</span>');
                                        }else if(elementName === 'gender'){
                                            $('form#form-update-infomation').find('.' + elementName).parents('.form-group').append('<span class="messageErrors" style="color:red">' + messageValue + '</span>');
                                        }else{
                                            $('form#form-update-infomation').find('.' + elementName).parents('.col-md-4').append('<span class="messageErrors" style="color:red">' + messageValue + '</span>');
                                        }
                                    });
                                });
                            }
                        } else if (result.status === true) {
                            $.notify({
                                title: "Cập nhật thành công : ",
                                message: ":D",
                                icon: 'fa fa-check'
                            },{
                                type: "info"
                            });
                            $(".button-edit").hide();
                            $("input").prop('disabled',true);
                            $("textarea").prop('disabled',true);
                            $("a#btn-update-inform").show();
                        }
                    }
                });
            });


            $("#avatar").change(function () {
                var changeImage = this;
                var urlCheckFile = "{{ route('personal-information-upload') }}";
                var formData = new FormData();
                var fileUpload = $(this);

                var file = this.files[0];
                formData.append('fileUpload', file);
                fileUpload.next("span.messageErrors").remove();
                $.ajax({
                    type: "post",
                    url: urlCheckFile,
                    data: formData,
                    cache: false,
                    contentType: false,
//                    enctype: 'multipart/form-data',
                    processData: false,
                    success: function (result) {
                        if (result.status === false) {
                            //show error list fields
                            if (result.arrMessages !== undefined) {
                                $.each(result.arrMessages, function (elementName, arrMessagesEveryElement) {
                                    $.each(arrMessagesEveryElement, function (messageType, messageValue) {
                                        fileUpload.after('<span class="messageErrors" style="color:red"><br>' + messageValue + '</span>');
                                    });
                                });
                                $("button#btn-save-inform").attr('disabled', true);
                            }
                        }else{
                            $("button#btn-save-inform").attr('disabled', false);
                            readURL(changeImage);
                        }
                    }
                });
            });
        });
    </script>

@endsection
