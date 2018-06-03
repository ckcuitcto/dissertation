@extends('layouts.default')

@section('content')
    <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class="fa fa-laptop"></i> Danh sách yêu cầu phúc khảo</h1>
                <p>Trường Đại học Công nghệ Sài Gòn</p>
            </div>
            <ul class="app-breadcrumb breadcrumb">
                <li class="breadcrumb-item"><a href="{{route('home')}}"><i class="fa fa-home fa-lg"></i></a></li>
                <li class="breadcrumb-item"> Danh sách yêu cầu phúc khảo</li>
            </ul>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="tile">
                    <table id="sampleTable" class="table table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Sinh viên</th>
                            <th>Lớp</th>
                            <th>Học kì</th>
                            <th>Năm học</th>
                            <th>Trạng thái</th>
                            <th>Ngày tạo</th>
                            <th>Tác vụ</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($remakings as $key =>  $request)
                            <tr>
                                <td>{{ $request->id }}</td>
                                <td>{{ $request->EvaluationForm->Student->User->name }}</td>
                                <td>{{ $request->EvaluationForm->Student->Classes->name }}</td>
                                <td> {{ $request->EvaluationForm->Semester->term }} </td>
                                <td> {{ $request->EvaluationForm->Semester->year_from."-".$request->EvaluationForm->Semester->year_to }} </td>
                                <td>{{  \App\Http\Controllers\Controller::getDisplayStatusRemaking($request->status)}}</td>
                                <td>{{  $request->created_at}}</td>
                                <td>
                                    @if($request->status !=  RESOLVED )
                                        <a class="btn btn-primary"
                                           href="{{ route('evaluation-form-show',[$request->EvaluationForm->id,'remaking=true&remaking_id='.$request->id]) }}">
                                            <i class="fa fa-edit" aria-hidden="true" style="color:white"></i> Chấm lại
                                        </a>
                                    @endif
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


@section('sub-javascript')
    <script type="text/javascript" src="{{ asset('template/js/plugins/jquery.dataTables.min.js') }} "></script>
    <script type="text/javascript" src="{{ asset('template/js/plugins/dataTables.bootstrap.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('template/js/plugins/bootstrap-notify.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('template/js/plugins/sweetalert.min.js') }}"></script>
    {{--<script type="text/javascript">$('#sampleTable').DataTable();</script>--}}
@endsection