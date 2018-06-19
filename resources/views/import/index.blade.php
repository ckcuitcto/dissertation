@extends('layouts.default')

@section('content')
    <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class="fa fa-laptop"></i> Danh sách file đã nhập</h1>
                <p>Trường Đại học Công nghệ Sài Gòn</p>
            </div>
            <ul class="app-breadcrumb breadcrumb">
                <li class="breadcrumb-item"><a href="{{route('home')}}"><i class="fa fa-home fa-lg"></i></a></li>
                <li class="breadcrumb-item"> Danh sách file</li>
            </ul>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="tile">
                    <table id="sampleTable" class="table table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tên file</th>
                            <th>Người nhập</th>
                            <th>Trạng thái</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($imports as $key => $value)
                            <tr>
                                <th>{{  $value->id }}</th>
                                <td>
                                    @if(file_exists(STUDENT_PATH_STORE.$value->file_path))
                                        <a href="{{ asset(STUDENT_PATH_STORE.$value->file_path) }}"> {{$value->file_name}}</a>
                                    @elseif(file_exists(STUDENT_LIST_EACH_SEMESTER_PATH.$value->file_path))
                                        <a href="{{ asset(STUDENT_LIST_EACH_SEMESTER_PATH.$value->file_path) }}"> {{$value->file_name}}</a>
                                    @else
                                        {{ $value->file_name }}
                                    @endif
                                </td>
                                <td>{{ $value->Staff->User->name }}</td>
                                <td>{{ $value->status }}</td>

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
    <script type="text/javascript" src="{{ asset('template/js/plugins/bootstrap-notify.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('template/js/plugins/sweetalert.min.js') }}"></script>
    {{--<script type="text/javascript">$('#sampleTable').DataTable();</script>--}}
@endsection