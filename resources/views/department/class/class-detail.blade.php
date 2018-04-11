<?php
/**
 * Created by PhpStorm.
 * User: Thai Duc
 * Date: 11-Apr-18
 * Time: 12:30 AM
 */
?>
@extends('layouts.default')
@section('content')
    <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class="fa fa-file-text-o"></i> Lớp {{ $class->name }}</h1>
                <p>Trường Đại học Công nghệ Sài Gòn</p>
            </div>
            <ul class="app-breadcrumb breadcrumb side">
                <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
                <li class="breadcrumb-item">Trang chủ</li>
                <li class="breadcrumb-item active"><a href="#"> Danh sách Khoa</a></li>
            </ul>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="tile">

                    <div class="tile user-settings">
                        <h4 class="line-head">Thông tin lớp {{ $class->name }}</h4>
                        <div class="row">
                            <div class="col-md-6">
                                <div>Sỉ số : {{ count($class->Students) }}</div>
                                <div>Cố vấn học tập : {{ $class->Staff->User->name }}</div>
                            </div>
                            <div class="col-md-6">
                                <div>- Khoa: {{ $class->Faculty->name }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="tile-body">
                        <table class="table table-hover table-bordered" id="sampleTable">
                            <thead>
                            <tr>
                                <th>MSSV</th>
                                <th>Sinh viên</th>
                                <th>Email</th>
                                <th>SĐT</th>
                                <th>Giới tính</th>
                                <th>Địa chỉ</th>
                                <th>Ngày sinh</th>
                                <th>Chức vụ</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($class->Students as $student)
                                <tr>
                                    <td><a href="{{ route('faculty-detail',$student->id) }}">{{ $student->user_id}} </a> </td>
                                    <td><a href="{{ route('faculty-detail',$student->id) }}">{{ $student->User->name }} </a> </td>
                                    <td>{{ $student->User->email }}</td>
                                    <td>{{ $student->User->phone_number }}</td>
                                    <td>{{ $student->User->gender }}</td>
                                    <td>{{ $student->User->address }}</td>
                                    <td>{{ $student->User->birthday }}</td>
                                    <td>{{ $student->User->Role->name }}</td>
                                </tr>
                            @endforeach
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
    <script type="text/javascript">$('#sampleTable').DataTable();</script>
@endsection