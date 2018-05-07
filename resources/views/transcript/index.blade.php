@extends('layouts.default')

@section('content')
    <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class="fa fa-laptop"></i> Tổng điểm cá nhân</h1>
                <p>Trường Đại học Công nghệ Sài Gòn</p>
            </div>
            <ul class="app-breadcrumb breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="fa fa-home fa-lg"></i></a></li>
                <li class="breadcrumb-item">Tổng điểm cá nhân</li>
            </ul>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="tile">
                    <h3 class="tile-title">Lưu ý</h3>
                    <div class="tile-body">
                        <div><i class="fa fa-file-text-o" aria-hidden="true"></i> Điểm cá nhân, Điểm lớp và Điểm khoa là
                            điểm đánh giá chưa tính điểm học tập
                        </div>
                        <div><i class="fa fa-file-text-o" aria-hidden="true"></i> Điểm tổng là điểm sau khi P.CTSV kiểm
                            duyệt đã bao gồm điểm học tập
                        </div>
                        <div><i class="fa fa-file-text-o" aria-hidden="true"></i> Xếp loại và Điểm tổng nếu có giá trị
                            là "_" thì đang đợi bổ sung điểm học tập
                        </div>

                    </div>
                    <div class="title-body" align="right">
                        <div>Họ và tên: {{ $user->name }}</div>
                        <div>Lớp: {{ $user->Student->Classes->name OR "" }}</div>
                        <div>MSSV: {{ $user->id }}</div>
                        <div>Khoa: {{ $user->Faculty->name OR "" }}</div>
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
                        @foreach($evaluationForms as $key => $evaluationForm)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $evaluationForm->Semester->term }}</td>
                                <td>{{ $evaluationForm->Semester->year_from . " - " . $evaluationForm->Semester->year_to }}</td>
                                <td>x</td>
                                <td>x</td>
                                <td>x</td>
                                <td>x</td>
                                <td>Trung Bình</td>
                                <td>Hoàn Thành</td>
                                <td>
                                    {{--@php dd($semester->EvaluationForm->where('student_id',$user->id)); @endphp--}}
                                    <a
                                        href="{{ route('evaluation-form-show',$evaluationForm->id) }}"
                                    >
                                        <i class="fa fa-eye" aria-hidden="true"></i>
                                    </a>
                                    <i class="fa fa-pencil" aria-hidden="true"></i>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
@endsection
