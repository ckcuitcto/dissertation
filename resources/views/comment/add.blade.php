@extends('layouts.default')
@section('content')
    <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class="fa fa-edit"></i> Đóng góp ý kiến đến ban quản lý nhà trường</h1>
                <p>Trường Đại học Công nghệ Sài Gòn</p>
            </div>
            <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><a href="{{route('home')}}"><i class="fa fa-home fa-lg"></i></a></li>
                <li class="breadcrumb-item"><a href="#">Trang góp ý</a></li>
            </ul>
        </div>
        <div class="row">
            <div class="col-md-12">
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            <form method="post" action="{{ route('comment-store') }}">
                <div class="tile">
                    <div class="row">
                        <div class="col-lg-12">
                            {!! csrf_field() !!}
                            <div class="form-group row">
                                <div class="col-md-3">
                                    <label for="exampleText">Tiêu đề</label>
                                </div>
                                <div class="col-md-8">
                                    <input class="form-control" id="title" name="title" type="text" placeholder="Nhập vào chủ đề">
                                    <small class="form-text text-muted" id="emailHelp"></small>
                                    @if ($errors->has('title'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('title') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>                  
                            <div class="form-group row">
                                <div class="col-md-3">
                                    <label for="exampleTextarea">Nội dung</label>
                                </div>
                                <div class="col-md-8">
                                    <textarea class="form-control" id="content" name="content" rows="5"></textarea>
                                    @if ($errors->has('content'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('content') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>                                                

                        </div>           
                    </div>
                    <div class="tile-footer">
                        <button class="btn btn-primary" type="submit" id="btn-save-comment" name="btn-save-comment">Gửi</button>
                        <button class="btn btn-danger" type="cancel">Hủy</button>
                    </div>
                </div>
            </form>
            </div>
        </div>
    </main>
@endsection
