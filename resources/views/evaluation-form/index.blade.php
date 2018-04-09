@extends('layouts.default')

@section('content')
    <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class="fa fa-file-text-o"></i> Phiếu đánh giá điểm rèn luyện</h1>
                <p>Trường Đại học Công nghệ Sài Gòn</p>
            </div>
            <ul class="app-breadcrumb breadcrumb side">
                <li class="breadcrumb-item"><a href="#"><i class="fa fa-home fa-lg"></i></a></li>
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


                        <table class="table table-hover table-bordered" id="sampleTable">
                            <tbody>
                              <tr>
                                <td colspan="7"><strong>Nội dung đánh giá</strong></td>
                                <td><strong>Thang điểm</strong></td>
                                <td><strong>Sinh viên tự đánh giá</strong></td>
                                <td><b>Tập thể lớp đánh giá</b></td>
                                <td><b>CVHT/GVCN kết luận điểm</b></td>
                              </tr>
                              <tr>
                                <td colspan="7">(1)</td>
                                <td>(2)</td>
                                <td>(3)</td>
                                <td>(4)</td>
                                <td>(5)</td>
                              </tr>

                            {{-- lấy ra tất  cả topic--}}
                            @foreach($topics as $key => $value)
                            {{-- chỉ hiện ra các topic to nhất để tránh trùng lặp--}}
                            @if (!$value->parent_id)

                              <tr>
                                  {{--nếu topic k có paren thì colspan cho giống--}}
                                  @if(!$value->parent_id)
                                  <td colspan="12"><b> {{ $value->title }}</b></td>
                                  @else
                                  <td> {{ $value->title }}</td>
                                  @endif
                              </tr>

                              {{-- nếu topic có topic con thì hiện ra--}}
                              @isset($value->TopicChild)
                              @foreach($value->TopicChild as $childValue)

                              <tr>
                                <td colspan="7"><b>{{ $childValue->title }}</b></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                              </tr>

                              {{-- hiện ra các tiêu chuẩn của các topic --}}
                              @isset($childValue->EvaluationCriterias)
                              @foreach($childValue->EvaluationCriterias as $evaluationCriteria)

                              <tr>
                                <td colspan="7"> {{ $evaluationCriteria->content }}
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
                                <td><input type="text" class="form-control"></td>
                                <td><input type="text" class="form-control"></td>
                                <td><input type="text" class="form-control"></td>
                              </tr>

                            @endforeach
                            @endisset
                            @endforeach
                            @endisset
                            {{-- hiện ra các  tiêu chuẩn của topic cha--}}
                            @isset($value->EvaluationCriterias)
                            @foreach($value->EvaluationCriterias as $evaluationCriteria)
                              <tr>
                                <td colspan="7"> {{ $evaluationCriteria->content }}
                                                    @isset($evaluationCriteria->detail)
                                                        {!!  \App\Http\Controllers\EvaluationFormController::handleDetail($evaluationCriteria->detail)  !!}
                                                    @endisset
                                                </td>
                                                @if($evaluationCriteria->mark_range_to)
                                <td> {{ $evaluationCriteria->mark_range_from ."-".$evaluationCriteria->mark_range_to ." điểm". $evaluationCriteria->unit }} </td>
                                                @else
                                <td> {{ $evaluationCriteria->mark_range_from ." điểm". $evaluationCriteria->unit }} </td>
                                                @endif
                                <td><input type="text"class="form-control"></td>
                                <td><input type="text"class="form-control"></td>
                                <td><input type="text"class="form-control"></td>
                              </tr>
                              @endforeach
                              @endisset
                          @endif
                      @endforeach
                              <tr>
                                  <td colspan="8">Tổng V. (Tối đa 10 điểm)</td>
                                  <td><input type="text"class="form-control"></td>
                                  <td><input type="text"class="form-control"></td>
                                  <td><input type="text"class="form-control"></td>
                              </tr>
                              <tr>
                                  <td colspan="7">Tổng cộng</td>
                                  <td>0 - 100</td>
                                  <td><input type="text"class="form-control"></td>
                                  <td><input type="text"class="form-control"></td>
                                  <td><input type="text"class="form-control"></td>
                              </tr>
                              <tr>
                                  <td colspan="7">Xếp loại</td>
                                  <td colspan="3" style="background-color:gray"></td>
                                  <td></td>
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
    <script type="text/javascript" src="{{ asset('template/js/plugins/jquery.dataTables.min.js') }} "></script>
    <script type="text/javascript" src="{{ asset('template/js/plugins/dataTables.bootstrap.min.js') }}"></script>
@endsection