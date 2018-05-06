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
            {{-- <div class="col-md-12"> --}}
                <div class="profile">
                    <div class="info col-md-4">
                        <img class="user-img" src="https://goo.gl/CXFpEd">
                        <div class="alert alert-primary" role="alert">
                            Cập nhật gần nhất
                        </div>
                        <div class="alert alert-success" role="alert">
                            23 giờ, ngày 23/04/2018
                        </div>
                        <div class="alert alert-info" role="alert">
                            <p>Yêu cầu chỉnh sửa các thông tin khác xin liên hệ: Phòng đào tạo</p>
                            <p>- Trụ sở: 180 Cao Lỗ, Phường 4, Quận 8, Tp. Hồ Chí Minh</p>
                            <p>- ĐT: 028 3850 5520</p>
                        </div>
                    </div>

                    <!-- <div class="tab-pane fade" id="user-settings"> -->
                    <div class="tile user-settings col-md-8">
                        <h4 class="line-head">Tổng quan</h4>
                        <form action="#" method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                            <div class="row mb-4">
                                <div class="col-md-2">
                                    <label class="control-label" for="readOnlyInput">Họ và tên</label>
                                </div>
                                <div class="col-md-4">
                                    <input class="form-control" id="name" type="text" readonly="" name="name" 
                                           value="{{$user->name}}" disabled>
                                </div>
                                <div class="col-md-2">
                                    <label class="control-label" for="readOnlyInput">Ngày sinh</label>
                                </div>
                                <div class="col-md-4">
                                    <input class="form-control" id="birthday" type="date"
                                           readonly="" name="birthday" value="{{$user->birthday}}" disabled>
                                </div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-2">
                                    <label>Lớp</label>
                                </div>
                                <div class="col-md-4">
                                    <input class="form-control" id="class" type="text"
                                        readonly="" name="class" value="{{$user->class}}" disabled>
                                </div>
                                <div class="col-md-2">
                                    <label>Chuyên ngành</label>
                                </div>
                                <div class="col-md-4">
                                    <input class="form-control" id="" type="text"
                                        readonly="" name="" value="" disabled>
                                </div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-2">
                                    <label>MSSV</label>
                                </div>
                                <div class="col-md-4">
                                    <input class="form-control" id="" type="text"
                                        readonly="" name="" value="" disabled>
                                </div>
                                <div class="col-md-2">
                                    <label>Niên khóa</label>
                                </div>
                                <div class="col-md-4">
                                    <input class="form-control" id="" type="text"
                                        readonly="" name="" value="" disabled>
                                </div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-2">
                                    <label>Khoa</label>
                                </div>
                                <div class="col-md-4">
                                    <input class="form-control" id="" type="text"
                                        readonly="" name="" value="" disabled>
                                </div>
                            </div>
                            <hr>
                            <h4 class="line-head">Thông tin cơ bản - liên hệ</h4>
                            <div class="row mb-4">
                                <div class="col-md-2">
                                    <label>Giới tính</label>
                                </div>
                                <div class="col-md-4">
                                    <input class="form-control" id="" type="text"
                                        name="" value="" disabled>
                                </div>
                                <div class="col-md-2">
                                    <label>Nơi sinh</label>
                                </div>
                                <div class="col-md-4">
                                    <input class="form-control" id="" type="text"
                                        name="" value="" disabled>
                                </div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-2">
                                    <label>Email</label>
                                </div>
                                <div class="col-md-10">
                                    <input class="form-control" id="" type="text"
                                        name="" value="" disabled>
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
                                    <button class="btn btn-primary" type="submit" id="btn-save-inform" name="btn-save-inform"> Lưu thông tin
                                    </button>
                                    <button class="btn btn-secondary" type="submit" id="btn-update-inform" name="btn-update-inform"> Sửa
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            {{-- </div>          --}}
        </div>
    </main>
@endsection