@extends('layouts.default')
@section('content')
    <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class="fa fa-file-text-o"></i> Danh sách sinh viên</h1>
                <p>Trường Đại học Công nghệ Sài Gòn</p>
            </div>
            <ul class="app-breadcrumb breadcrumb side">
                <li class="breadcrumb-item"><a href="{{route('home')}}"><i class="fa fa-home fa-lg"></i></a></li>
                <li class="breadcrumb-item active"> Danh sách Sinh viên</li>
            </ul>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="tile">
                    <div class="tile-body">
                        <table class="table table-hover table-bordered" id="sampleTable">
                            <thead>
                            <tr>
                                <th>STT</th>
                                <th>Tên</th>
                                <th>Chức vụ</th>
                                <th>Lớp</th>
                                <th>Khoa</th>
                                <th>Khóa</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if($students != false)
                            @foreach($students as $key => $student)
                                <tr>
                                    <td> {{ $student->user_id }}</td>
                                    <td>
                                        <a href="{{route('transcript-show',$student->id )}}"> {{ $student->User->name }} </a>
                                    </td>
                                    <td>{{ $student->User->Role->display_name }}</td>
                                    <td>{{ $student->Classes->name or "" }}</td>
                                    <td>{{ $student->User->Faculty->name or "" }}</td>
                                    <td> {{ $student->academic_year_from  ." - ". $student->academic_year_to }}</td>
                                </tr>
                            @endforeach
                            @endisset
                            </tbody>
                        </table>
                    </div>
                    {{ $students->links('vendor.pagination.bootstrap-4') }}
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
