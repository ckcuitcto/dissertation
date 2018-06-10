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
            <form method="post" action="{{ route('comment-store') }}">
                {!! csrf_field() !!}
                <div class="tile">
                    <div class="tile-body">
                        <form>
                            <div class="form-group">
                                <label class="control-label">Tiêu đề</label>
                                <input class="form-control" id="title" name="title" type="text" placeholder="Nhập vào chủ đề">
                                <small class="form-text text-muted" id="emailHelp"></small>
                                @if ($errors->has('title'))
                                    <span class="help-block">
                                            <strong style="color:red">{{ $errors->first('title') }}</strong>
                                        </span>
                                @endif
                            </div>
                            <div class="form-group">
                                <label class="control-label">Nội dung</label>
                                <textarea class="form-control" id="content" name="content" rows="5"></textarea>
                                @if ($errors->has('content'))
                                    <span class="help-block">
                                            <strong style="color:red">{{ $errors->first('content') }}</strong>
                                        </span>
                                @endif
                            </div>
                        </form>
                    </div>
                    <div class="tile-footer">
                        <button class="btn btn-primary" type="submit"><i class="fa fa-fw fa-lg fa-check-circle"></i>Gửi</button>
                        &nbsp;&nbsp;&nbsp;
                    </div>
                </div>
            </form>
            </div>
        </div>
    </main>
@endsection

@section('sub-javascript')
    <script>
        CKEDITOR.replace('content');
    </script>
@endsection
