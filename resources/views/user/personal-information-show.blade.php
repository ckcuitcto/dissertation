<?php
/**
 * Created by PhpStorm.
 * User: huynh
 * Date: 10-May-18
 * Time: 10:56 PM
 */
?>
@extends('layouts.default')

@section('content')
    <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class="fa fa-edit"></i> Thông tin sinh viên</h1>
                <p>Trường đại học Công nghệ Sài Gòn</p>
            </div>
            <ul class="app-breadcrumb breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home')}}"><i class="fa fa-home fa-lg"></i></a></li>
                <li class="breadcrumb-item">Thông tin sinh viên</li>
            </ul>
        </div>

        <div class="row user">

            {{--<div class="col-md-12">--}}
            {{--<div class="profile">--}}
            {{--<div class="info"><img class="user-img" src="https://s3.amazonaws.com/uifaces/faces/twitter/jsa/128.jpg">--}}
            {{--<h4>John Doe</h4>--}}
            {{--<p>FrontEnd Developer</p>--}}
            {{--</div>--}}
            {{--<div class="cover-image"></div>--}}
            {{--</div>--}}
            {{--</div>--}}

            <div class="col-md-3">
                <div class="tile p-0">
                    <div class="info">
                        <img id="imageChange" style="width: 100%;" class="user-image" src="https://goo.gl/CXFpEd">
                        <input type='file' id="avatar" />
                        <div class="alert alert-primary" role="alert">
                            Cập nhật gần nhất
                        {{--</div>--}}
                        {{--<div class="alert alert-success" role="alert">--}}
                         <p>  {{ $user->updated_at OR "" }} </p>
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
                <form action="#" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <label>MSSV</label>
                            <input class="form-control" id="" type="text"
                                    name="" value="{{ $user->id }}" disabled>
                        </div>

                        <div class="col-md-4">
                            <label>Họ và tên</label>
                            <input class="form-control name" id="name" type="text"  name="name"
                                   value="{{$user->name}}" disabled>
                        </div>
                        <div class="col-md-4">
                            <label>Ngày sinh</label>
                            <input class="form-control birthday" id="birthday" type="date"
                                    name="birthday" value="{{$user->birthday}}" disabled>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-4">
                            <label>Lớp</label>
                            <input class="form-control class" id="class" type="text"
                                    name="class" value="{{$user->class}}" disabled>
                        </div>
                        <div class="col-md-4">
                            <label>Niên khóa</label>
                            <input class="form-control" id="" type="text"
                                    name="" value="" disabled>
                        </div>
                        <div class="col-md-4">
                            <label>Khoa</label>
                            <input class="form-control" id="" type="text"
                                   name="" value="" disabled>
                        </div>
                    </div>

                    <hr>
                    <h4 class="line-head">Thông tin cơ bản - liên hệ</h4>

                    <div class="row mb-4">
                        <div class="col-md-4">
                            <label>Giới tính</label>
                            <input class="form-control" id="" type="text" name="" value="" disabled>
                        </div>
                        <div class="col-md-4">
                            <label>Nơi sinh</label>
                            <input class="form-control" id="" type="text" name="" value="" disabled>
                        </div>
                        <div class="col-md-4">
                            <label>Email</label>
                            <input class="form-control" id="" type="text" name="" value="" disabled>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-4">
                            <label>Nơi sinh</label>
                            <input class="form-control" id="" type="text" name="" value="" disabled>
                        </div>
                        <div class="col-md-4">
                            <label>Điện thoại</label>
                            <input class="form-control" id="" type="text" name="" value="" disabled>
                        </div>
                        <div class="col-md-4">
                            <label>ĐT báo tin</label>
                            <input class="form-control" id="" type="text" name="" value="" disabled>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-4">
                            <label>Số CMND </label>
                            <input class="form-control" id="" type="text" name="" value="" disabled>
                        </div>
                        <div class="col-md-4">
                            <label>ĐT báo tin</label>
                            <input class="form-control" id="" type="text" name="" value="" disabled>
                        </div>
                    </div>

                    {{-- <div class="row mb-4">
                        <div class="col-md-2">
                            <label>Quốc tịch</label>
                        </div>
                        <div class="col-md-10">
                            <input class="form-control" type="text">
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-2">
                            <label>Dân tộc</label>
                        </div>
                        <div class="col-md-4">
                            <input class="form-control" type="text">
                        </div>
                        <div class="col-md-2">
                            <label>Tôn giáo</label>
                        </div>
                        <div class="col-md-4">
                            <input class="form-control" type="text">
                        </div>
                    </div> --}}
                    {{--<h4 class="line-head">Địa chỉ liên lạc</h4>--}}
                    {{--<div class="row mb-4">--}}
                        {{--<div class="col-md-2">--}}
                            {{--<label>Địa chỉ liên lạc</label>--}}
                        {{--</div>--}}
                        {{--<div class="col-md-10">--}}
                            {{--<input class="form-control" type="text">--}}
                        {{--</div>--}}
                    {{--</div>--}}

                    {{--<div class="row mb-4">--}}
                        {{--<div class="col-md-2">--}}
                            {{--<label>Tỉnh/TP</label>--}}
                        {{--</div>--}}
                        {{--<div class="col-md-4">--}}
                            {{--<input class="form-control" type="text">--}}
                        {{--</div>--}}
                        {{--<div class="col-md-2">--}}
                            {{--<label>Quận/huyện</label>--}}
                        {{--</div>--}}
                        {{--<div class="col-md-4">--}}
                            {{--<input class="form-control" type="text">--}}
                        {{--</div>--}}
                    {{--</div>--}}

                    {{-- <hr>
                    <h4 class="line-head">Địa chỉ hộ khẩu</h4>
                    <div class="row mb-4">
                        <div class="col-md-2">
                            <label>Địa chỉ hộ khẩu</label>
                        </div>
                        <div class="col-md-10">
                            <input class="form-control" type="text">
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-2">
                            <label>Tỉnh/TP</label>
                        </div>
                        <div class="col-md-4">
                            <input class="form-control" type="text">
                        </div>
                        <div class="col-md-2">
                            <label>Quận/huyện</label>
                        </div>
                        <div class="col-md-4">
                            <input class="form-control" type="text">
                        </div>
                    </div>

                    <hr> --}}
                    {{-- <h4 class="line-head">Thông tin thân nhân</h4>
                    <div class="row mb-4">
                        <div class="col-md-2">
                            <label>Họ tên cha</label>
                        </div>
                        <div class="col-md-4">
                            <input class="form-control" type="text">
                        </div>
                        <div class="col-md-2">
                            <label>Điện thoại</label>
                        </div>
                        <div class="col-md-4">
                            <input class="form-control" type="text">
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-2">
                            <label>Nghề nghiệp</label>
                        </div>
                        <div class="col-md-4">
                            <input class="form-control" type="text">
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-2">
                            <label>Họ tên mẹ</label>
                        </div>
                        <div class="col-md-4">
                            <input class="form-control" type="text">
                        </div>
                        <div class="col-md-2">
                            <label>Điện thoại</label>
                        </div>
                        <div class="col-md-4">
                            <input class="form-control" type="text">
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-2">
                            <label>Nghề nghiệp</label>
                        </div>
                        <div class="col-md-4">
                            <input class="form-control" type="text">
                        </div>
                    </div> --}}

                    <div class="row mb-10">
                        <div class="col-md-12">
                            <button class="btn btn-primary" type="submit" id="btn-save-inform"
                                    name="btn-save-inform"> Lưu thông tin
                            </button>
                            <button class="btn btn-secondary" type="submit" id="btn-update-inform"
                                    name="btn-update-inform"> Sửa
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            {{-- </div>          --}}
        </div>
    </main>
@endsection

@section('sub-javascript')
    <script>
        $(document).ready(function () {
            $("#avatar").change(function () {
                readURL(this);
            });
        });
    </script>

@endsection
