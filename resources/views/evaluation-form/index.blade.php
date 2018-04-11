<?php
/**
 * Created by PhpStorm.
 * User: Thai Duc
 * Date: 06-Apr-18
 * Time: 8:02 PM
 */
?>

@extends('layouts.default')

@section('content')
    <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class="fa fa-file-text-o"></i> Phiếu đánh giá điểm rèn luyện</h1>
                <p>Trường Đại học Công nghệ Sài Gòn</p>
            </div>
            <ul class="app-breadcrumb breadcrumb side">
                <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
                <li class="breadcrumb-item">Trang chủ</li>
                <li class="breadcrumb-item active"><a href="#">Phiếu đánh giá điểm rèn luyện</a></li>
            </ul>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="tile">
                    <div class="tile-body">

                        <div class="tile user-settings">
                            <h4 class="line-head">Thông tin sinh viên</h4>
                            <div class="row">
                                <div class="col-md-6">
                                    <div>- Họ và tên: Trần Ngọc Gia Hân</div>
                                    <div>- Lớp: D14TH02</div>
                                    <div>- MSSV: DH51401681</div>
                                </div>
                                <div class="col-md-6">
                                    <div>- Khoa: Công nghệ thông tin</div>
                                    <div>- Niên khóa: 2014 - 2018</div>
                                </div>
                            </div>
                        </div>
                        <table class="table table-hover table-bordered">
                       {{--<table class="table table-hover table-bordered" id="sampleTable">--}}
                            <thead>
                            <tr>
                                <th style="width: 71%;">Nội dung đánh giá</th>
                                <th style="width: 5%;">Thang điểm</th>
                                <th style="width: 8%;">Sinh viên tự đánh giá</th>
                                <th style="width: 8%;">Tập thể lớp đánh giá</th>
                                <th style="width: 8%;">CVHT/GVCN kết luận điểm</th>
                            </tr>
                            </thead>
                            <tbody>
                            {{-- lấy ra tất  cả topic--}}
                            @foreach($topics as $key => $value)
                                {{-- chỉ hiện ra các topic to nhất để tránh trùng lặp--}}
                                @if (!$value->parent_id)
                                    <tr>
                                        {{--nếu topic k có paren thì colspan cho giống--}}
                                        @if(!$value->parent_id)
                                            <td colspan="5"><b> {{ $value->title }}</b></td>
                                        @else
                                            <td> {{ $value->title }}</td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        @endif
                                    </tr>
                                    {{-- nếu topic có topic con thì hiện ra--}}
                                    @isset($value->TopicChild)
                                        @foreach($value->TopicChild as $childValue)
                                            <tr>
                                                <td><b>{{ $childValue->title }}</b></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            {{-- hiện ra các tiêu chuẩn của các topic --}}
                                            @isset($childValue->EvaluationCriterias)
                                                @foreach($childValue->EvaluationCriterias as $evaluationCriteria)
                                                    <tr>
                                                        <td> {{ $evaluationCriteria->content }}
                                                            {{-- nếu có chi tiết thì hiện ra--}}
                                                            @isset($evaluationCriteria->detail)
                                                                {!!  \App\Http\Controllers\EvaluationFormController::handleDetail($evaluationCriteria->detail)  !!}
                                                            @endisset
                                                        </td>
                                                        @if($evaluationCriteria->mark_range_to)
                                                            <td> {{ $evaluationCriteria->mark_range_from ."-".$evaluationCriteria->mark_range_to ." điểm". $evaluationCriteria->unit }} </td>
                                                        @else
                                                            <td> {{ $evaluationCriteria->mark_range_from ." điểm". $evaluationCriteria->unit }} </td>
                                                        @endif
                                                        <td><input type="number" style="width: 50px"></td>
                                                        <td><input type="number" style="width: 50px"></td>
                                                        <td><input type="number" style="width: 50px"></td>

                                                    </tr>
                                                @endforeach
                                            @endisset
                                        @endforeach
                                    @endisset
                                    {{-- hiện ra các  tiêu chuẩn của topic cha--}}
                                    @isset($value->EvaluationCriterias)
                                        @foreach($value->EvaluationCriterias as $evaluationCriteria)
                                            <tr>
                                                <td> {{ $evaluationCriteria->content }}
                                                    @isset($evaluationCriteria->detail)
                                                        {!!  \App\Http\Controllers\EvaluationFormController::handleDetail($evaluationCriteria->detail)  !!}
                                                    @endisset
                                                </td>
                                                @if($evaluationCriteria->mark_range_to)
                                                    <td> {{ $evaluationCriteria->mark_range_from ."-".$evaluationCriteria->mark_range_to ." điểm". $evaluationCriteria->unit }} </td>
                                                @else
                                                    <td> {{ $evaluationCriteria->mark_range_from ." điểm". $evaluationCriteria->unit }} </td>
                                                @endif
                                                <td><input type="number" style="width: 50px"></td>
                                                <td><input type="number" style="width: 50px"></td>
                                                <td><input type="number" style="width: 50px"></td>

                                            </tr>
                                        @endforeach
                                    @endisset
                                @endif
                            @endforeach

                            <tr>
                                <th colspan="2"> Tổng V. (Tối đa 10 điểm)</th>
                                <th></th> <th></th> <th></th>
                            </tr>
                            <tr>
                                <th> Tổng cộng</th>
                                <th> 0 - 100</th> <th></th> <th></th> <th></th><th></th>
                            </tr>
                            <tr>
                                <th> Xếp loại</th>
                                <th colspan="3"></th><th></th>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>

@endsection

@section('sub-javascript')

    {{--<!-- Page specific javascripts-->--}}
    {{--<!-- Data table plugin-->--}}
    <script type="text/javascript" src="{{ asset('template/js/plugins/jquery.dataTables.min.js') }} "></script>
    <script type="text/javascript" src="{{ asset('template/js/plugins/dataTables.bootstrap.min.js') }}"></script>
    <script type="text/javascript">$('#sampleTable').DataTable();</script>
    {{--<!-- Google analytics script-->--}}

@endsection