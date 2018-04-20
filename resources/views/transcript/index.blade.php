@extends('layouts.default')

@section('content')
    <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class="fa fa-laptop"></i> Tổng điểm cá nhân</h1>
                <p>Trường Đại học Công nghệ Sài Gòn</p>
            </div>
            <ul class="app-breadcrumb breadcrumb">
                <li class="breadcrumb-item"><a href="#"><i class="fa fa-home fa-lg"></i></a></li>
                <li class="breadcrumb-item"><a href="#">Tổng điểm cá nhân</a></li>
            </ul>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="tile">
                    <h3 class="tile-title">Lưu ý</h3>
                    <div class="tile-body">
                        <div><i class="fa fa-file-text-o" aria-hidden="true"></i> Điểm cá nhân, Điểm lớp và Điểm khoa là điểm đánh giá chưa tính điểm học tập</div>
                        <div><i class="fa fa-file-text-o" aria-hidden="true"></i> Điểm tổng là điểm sau khi P.CTSV kiểm duyệt đã bao gồm điểm học tập</div>
                        <div><i class="fa fa-file-text-o" aria-hidden="true"></i> Xếp loại và Điểm tổng nếu có giá trị là "_" thì đang đợi bổ sung điểm học tập</div>
                    </div>
                    <div class="title-body" style="text-align:right">
                        <div>Họ và tên: Trần Ngọc Gia Hân</div>
                        <div>Lớp: D14-TH02</div>
                        <div>MSSV: DH51401681</div>
                        <div>Khoa: Công nghệ thông tin</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="tile">
                    <table class="table table-hover table-bordered">
                        <tbody>
                        <tr>
                            <td rowspan="2">STT</td>
                            <td rowspan="2">Học Kỳ</td>
                            <td rowspan="2">Năm Học</td>
                            <td colspan="4">Điểm</td>
                            <td rowspan="2">Xếp Loại</td>
                            <td rowspan="2">Tình Trạng</td>
                            <td rowspan="2">Tác Vụ</td>
                        </tr>
                        <tr>
                            <td>Cá Nhân</td>
                            <td>Lớp</td>
                            <td>Khoa</td>
                            <td>Tổng</td>
                        </tr>
                        <tr>
                            <td>1</td>
                            <td>1</td>
                            <td>2017-2017</td>
                            <td>60</td>
                            <td>65</td>
                            <td>61</td>
                            <td>70</td>
                            <td>Trung Bình</td>
                            <td>Hoàn Thành</td>
                            <td>
                                <i class="fa fa-eye" aria-hidden="true"></i>
                                <i class="fa fa-pencil" aria-hidden="true"></i>
                            </td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>2</td>
                            <td>2017-2018</td>
                            <td>60</td>
                            <td>65</td>
                            <td>61</td>
                            <td>70</td>
                            <td>Trung Bình</td>
                            <td>Hoàn Thành</td>
                            <td>
                                <span><i class="fa fa-eye" aria-hidden="true"></i></span>
                                <span><i class="fa fa-pencil" aria-hidden="true"></i></span>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
@endsection
