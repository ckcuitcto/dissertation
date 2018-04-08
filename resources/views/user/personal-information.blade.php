<?php
/**
 * Created by PhpStorm.
 * User: Thai Duc
 * Date: 06-Apr-18
 * Time: 9:06 PM
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
                <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
                <li class="breadcrumb-item">Trang chủ</li>
                <li class="breadcrumb-item"><a href="#">Thông tin sinh viên</a></li>
            </ul>
        </div>

        <div class="row user">
            <div class="col-md-12">
                <div class="profile">
                    <div class="info"><img class="user-img" src="https://goo.gl/CXFpEd">
                        <h4>Trần Ngọc Gia Hân</h4>
                        <p>D14-TH02</p>
                        <p>DH51401681</p>
                    </div>

                    <!-- <div class="tab-pane fade" id="user-settings"> -->
                    <div class="tile user-settings">
                        <h4 class="line-head">Tổng quan</h4>
                        <form>
                            <div class="row mb-4">
                                <div class="col-md-2">
                                    <label class="control-label" for="readOnlyInput">Họ và tên</label>
                                </div>
                                <div class="col-md-4">
                                    <input class="form-control" id="readOnlyInput" type="text"
                                           placeholder="Trần Ngọc Gia Hân" readonly="">
                                </div>
                                <div class="col-md-2">
                                    <label class="control-label" for="readOnlyInput">Ngày sinh</label>
                                </div>
                                <div class="col-md-4">
                                    <input class="form-control" id="readOnlyInput" type="text" placeholder="26-03-1996"
                                           readonly="">
                                </div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-2">
                                    <label>Lớp</label>
                                </div>
                                <div class="col-md-4">
                                    <input class="form-control" id="readOnlyInput" type="text" placeholder="D14-TH02"
                                           readonly="">
                                </div>
                                <div class="col-md-2">
                                    <label>Chuyên ngành</label>
                                </div>
                                <div class="col-md-4">
                                    <input class="form-control" id="readOnlyInput" type="text"
                                           placeholder="Full stack deverloper" readonly="">
                                </div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-2">
                                    <label>MSSV</label>
                                </div>
                                <div class="col-md-4">
                                    <input class="form-control" id="readOnlyInput" type="text" placeholder="DH51401681"
                                           readonly="">
                                </div>
                                <div class="col-md-2">
                                    <label>Niên khóa</label>
                                </div>
                                <div class="col-md-4">
                                    <input class="form-control" type="text" placeholder="2014-2018" readonly="">
                                </div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-2">
                                    <label>Khoa</label>
                                </div>
                                <div class="col-md-4">
                                    <input class="form-control" id="readOnlyInput" type="text"
                                           placeholder="Công nghệ thông tin" readonly="">
                                </div>
                            </div>
                            <hr>
                            <h4 class="line-head">Thông tin cơ bản - liên hệ</h4>
                            <div class="row mb-4">
                                <div class="col-md-2">
                                    <label>Giới tính</label>
                                </div>
                                <div class="col-md-4">
                                    <input class="form-control" type="text">
                                </div>
                                <div class="col-md-2">
                                    <label>Nơi sinh</label>
                                </div>
                                <div class="col-md-4">
                                    <input class="form-control" type="text">
                                </div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-2">
                                    <label>Email</label>
                                </div>
                                <div class="col-md-10">
                                    <input class="form-control" type="text">
                                </div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-2">
                                    <label>Điện thoại</label>
                                </div>
                                <div class="col-md-4">
                                    <input class="form-control" type="text">
                                </div>
                                <div class="col-md-2">
                                    <label>ĐT báo tin</label>
                                </div>
                                <div class="col-md-4">
                                    <input class="form-control" type="text">
                                </div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-2">
                                    <label>Số CMND</label>
                                </div>
                                <div class="col-md-3">
                                    <input class="form-control" type="text">
                                </div>
                                <div class="col-md-3">
                                    <input class="form-control" type="date">
                                </div>
                                <div class="col-md-4">
                                    <input class="form-control" type="text">
                                </div>
                            </div>

                            <div class="row mb-4">
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
                            </div>

                            <hr>
                            <h4 class="line-head">Địa chỉ liên lạc</h4>
                            <div class="row mb-4">
                                <div class="col-md-2">
                                    <label>Địa chỉ liên lạc</label>
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

                            <hr>
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

                            <hr>
                            <h4 class="line-head">Thông tin thân nhân</h4>
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
                            </div>

                            <div class="row mb-10">
                                <div class="col-md-12">
                                    <button class="btn btn-primary" type="button"><i
                                                class="fa fa-fw fa-lg fa-check-circle"></i> Lưu thông tin
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <!-- </div> -->
                    <!-- <div class="cover-image"></div> -->
                </div>
            </div>

            {{--<div class="tab-pane fade" id="user-settings">--}}
            {{--<div class="tile user-settings">--}}
            {{--<h4 class="line-head">Settings</h4>--}}
            {{--<form>--}}
            {{--<div class="row mb-4">--}}
            {{--<div class="col-md-4">--}}
            {{--<label>First Name</label>--}}
            {{--<input class="form-control" type="text">--}}
            {{--</div>--}}
            {{--<div class="col-md-4">--}}
            {{--<label>Last Name</label>--}}
            {{--<input class="form-control" type="text">--}}
            {{--</div>--}}
            {{--</div>--}}
            {{--<div class="row">--}}
            {{--<div class="col-md-8 mb-4">--}}
            {{--<label>Email</label>--}}
            {{--<input class="form-control" type="text">--}}
            {{--</div>--}}
            {{--<div class="clearfix"></div>--}}
            {{--<div class="col-md-8 mb-4">--}}
            {{--<label>Mobile No</label>--}}
            {{--<input class="form-control" type="text">--}}
            {{--</div>--}}
            {{--<div class="clearfix"></div>--}}
            {{--<div class="col-md-8 mb-4">--}}
            {{--<label>Office Phone</label>--}}
            {{--<input class="form-control" type="text">--}}
            {{--</div>--}}
            {{--<div class="clearfix"></div>--}}
            {{--<div class="col-md-8 mb-4">--}}
            {{--<label>Home Phone</label>--}}
            {{--<input class="form-control" type="text">--}}
            {{--</div>--}}
            {{--</div>--}}
            {{--<div class="row mb-10">--}}
            {{--<div class="col-md-12">--}}
            {{--<button class="btn btn-primary" type="button"><i class="fa fa-fw fa-lg fa-check-circle"></i> Save</button>--}}
            {{--</div>--}}
            {{--</div>--}}
            {{--</form>--}}
            {{--</div>--}}
            {{--</div>--}}
        </div>
    </main>
@endsection

