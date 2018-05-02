@extends('layouts.default')

@section('content')
    <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class="fa fa-file-text-o"></i> Phiếu đánh giá điểm rèn luyện</h1>
                <p>Trường Đại học Công nghệ Sài Gòn</p>
            </div>
            <ul class="app-breadcrumb breadcrumb side">
                <li class="breadcrumb-item"><a href="{{ route('home')}}"><i class="fa fa-home fa-lg"></i></a></li>
                <li class="breadcrumb-item">Phiếu đánh giá điểm rèn luyện</li>
            </ul>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="tile">
                    <div class="tile-body">
                        <div class="tile user-settings">
                            <h4 class="line-head">Thông tin sinh viên</h4>
                            <div class="row">
                                {{--<div class="col-md-6">--}}
                                    {{--<div>- Họ và tên: {{ $form->Student->User->name }}</div>--}}
                                    {{--<div>- Lớp: {{$form->Student->Classes->name OR ""}}</div>--}}
                                    {{--<div>- MSSV: {{ $form->Student->user_id }}</div>--}}
                                {{--</div>--}}
                                {{--<div class="col-md-6">--}}
                                    {{--<div>- Khoa: {{ $form->Student->Classes->Faculty->name }}</div>--}}
                                    {{--<div>- Niên khóa: {{ $form->Student->academic_year_from . " - " . $form->Student->academic_year_to }}</div>--}}
                                {{--</div>--}}
                            </div>
                        </div>
                        <table class="table table-hover table-bordered">
                            <tbody>
                            <tr>
                                <td><strong>Nội dung đánh giá</strong></td>
                                <td><strong>Thang điểm</strong></td>
                                @foreach($listRoleCanMark as $role)
                                <td><b> {{ $role->display_name }}</b></td>
                                @endforeach
                            </tr>
                            {{--<tr class="text-center">--}}
                                {{--<td>(1)</td>--}}
                                {{--<td>(2)</td>--}}
                                {{--<td>(3)</td>--}}
                                {{--<td>(4)</td>--}}
                                {{--<td>(5)</td>--}}
                            {{--</tr>--}}
                            @foreach($evaluationCriterias as $key => $valueLevel1)
                                    <tr>
                                            <td colspan="{{ 2 + count($listRoleCanMark) }}"><b> {{ $valueLevel1->content }}</b></td>
                                    </tr>
                                    @foreach($valueLevel1->Child as $valueLevel2)
                                        <tr>
                                            @if($valueLevel2->detail)
                                                <td class='detail-evaluation-form' >
                                                    {{ $valueLevel2->content }}
                                                    {!!  \App\Http\Controllers\Evaluation\EvaluationFormController::handleDetail($valueLevel2->detail)  !!}
                                                </td>
                                            @else
                                                <td>
                                                    {{ $valueLevel2->content }}
                                                </td>
                                            @endif
                                            <td>
                                                {{ $valueLevel2->mark_range_display OR "" }}
                                            </td>
                                                @foreach($listRoleCanMark as $role)
                                                    <td><input type="text" disabled="true" class="form-control {{ $role->name }}"></td>
                                                @endforeach
                                        </tr>
                                        @foreach($valueLevel2->Child as $valueLevel3)
                                            <tr>
                                                @if($valueLevel3->detail)
                                                <td class='detail-evaluation-form' >
                                                    {{ $valueLevel3->content }}
                                                    {!!  \App\Http\Controllers\Evaluation\EvaluationFormController::handleDetail($valueLevel3->detail)  !!}
                                                </td>
                                                @else
                                                    <td>
                                                        {{ $valueLevel3->content }}
                                                    </td>
                                                @endif
                                                <td>{{ $valueLevel3->mark_range_display OR "" }}</td>
                                                @foreach($listRoleCanMark as $role)
                                                    <td><input type="text" disabled="true" class="form-control  {{ $role->name }}"></td>
                                                @endforeach
                                            </tr>
                                        @endforeach
                                    @endforeach
                                    <tr>
                                        <td colspan="2" class="text-center">
                                            <b>Tổng {{ \App\Helpers\Convert::numberToRomanRepresentation($key +1) }}.
                                                (Tối đa {{ $valueLevel1->max_score }} điểm) </b></td>
                                        @foreach($listRoleCanMark as $role)
                                            <td><input type="text" disabled="true" class="form-control {{ $role->name }}"></td>
                                        @endforeach
                                    </tr>

                            @endforeach

                            <tr>
                                <td>Tổng cộng</td>
                                <td>0 - 100</td>
                                @foreach($listRoleCanMark as $role)
                                    <td><input type="text" disabled="true" class="form-control {{ $role->name }}"></td>
                                @endforeach
                            </tr>
                            <tr>
                                <td>Xếp loại</td>
                                <td colspan="{{ count($listRoleCanMark) }}" style="background-color:gray"></td>
                                <td></td>
                            </tr>
                            </tbody>
                        </table>
                        <div align="right">
                            <button class="btn btn-primary" type="submit">Lưu</button>
                            <button class="btn btn-secondary" type="cancel">Hủy</button>
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

    <script type="text/javascript">
        $(document).ready(function(){
            var roleLogin = "{{ $user->Role->name }}";-
            $("input."+roleLogin).removeAttr('disabled');
        });
        {{--document.getElementsByClassName(roleLogin).setAttribute("disabled","false");--}}
    </script>
@endsection