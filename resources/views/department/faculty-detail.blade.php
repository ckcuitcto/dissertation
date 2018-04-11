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
                <h1><i class="fa fa-file-text-o"></i> Danh sách các lớp thuộc khoa {{ $faculty->name }}</h1>
                <p>Trường Đại học Công nghệ Sài Gòn</p>
            </div>
            <ul class="app-breadcrumb breadcrumb side">
                <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
                <li class="breadcrumb-item">Trang chủ</li>
                <li class="breadcrumb-item active"><a href="#"> Khoa {{ $faculty->name }} </a></li>
            </ul>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="tile">
                    <div class="tile faculty-setting">
                        <div id="faculty-info">
                            <h4 class="line-head">Thông tin khoa {{ $faculty->name }}</h4>
                            <div class="row">
                                <div class="col-md-10">
                                    <div class="fo"></div>
                                    <div>Số lớp : {{ count($faculty->Classes) }}</div>
                                </div>
                                <div class="col-md-2">
                                    <button class="btn btn-primary" id="btnEditFaculty" type="button"><i class="fa fa-pencil-square-o" aria-hidden="true"></i>Sửa</button>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    @include('alert.success')
                                </div>
                            </div>
                        </div>
                        <div id="faculty-edit" @if (!$errors->any()) style="display: none" @endif>
                            <form action="{{ route('faculty-edit',$faculty->id ) }}" method="post" id="form-faculty-edit">
                                {!! csrf_field() !!}
                                <div class="form-group">
                                    <label class="control-label">Tên khoa</label>
                                    <input class="form-control" name="name" value="{{ old('name') }}" id="name" required type="text" placeholder="Nhập tên mới của Khoa">
                                    @if ($errors->has('name'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                    @endif
                                </div>
                                <button class="btn btn-primary" type="submit"><i class="fa fa-fw fa-lg fa-check-circle"></i>Sửa</button>
                                &nbsp;&nbsp;&nbsp;
                                <a class="btn btn-secondary" id="btnCancelEdit" href="#"><i class="fa fa-fw fa-lg fa-times-circle"></i>Hủy</a>
                            </form>
                        </div>
                    </div>
                    <div class="tile-body">
                        <table class="table table-hover table-bordered" id="sampleTable">
                            <thead>
                            <tr>
                                <th>Lớp</th>
                                <th>Số lượng sinh viên</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($faculty->classes as $class)
                                <tr>
                                    <td><a href="{{ route('class-detail',$class->id) }}">{{ $class->name }} </a> </td>
                                    <td>{{ count($class->Students) }}</td>
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

    <script type="text/javascript" src="{{ asset('template/js/plugins/bootstrap-notify.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('template/js/plugins/sweetalert.min.js') }}"></script>

    <script type="text/javascript">
        $(document).ready(function(){
            $('div.alert-success').delay(2000).slideUp();

            $('#btnEditFaculty').click(function(){
                $("#faculty-edit").fadeToggle();
            });

            $('#btnCancelEdit').click(function(){
                $("#faculty-edit").fadeOut("slow");
            });

//            $('#form-faculty-edit').submit(function(){
//                $.ajax({
//                    type: "POST",
//                    url: $(this).attr('action').val(),
//                    data: $(this).serialize(),
//                    success: function(data) {
//                        alert(data);
//                        if(data == 'success') {
//                            window.location.reload();
//                            $.notify({
//                                title: "Update Complete : ",
//                                message: "Something cool is just updated!",
//                                icon: 'fa fa-check'
//                            }, {
//                                type: "info"
//                            });
//                        }
//                    },error:function () {
//                        alert(111);
//                    }
//                })
//            });
        });

    </script>
@endsection