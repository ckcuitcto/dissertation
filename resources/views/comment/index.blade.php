@extends('layouts.default')
@section('content')
    <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class="fa fa-edit"></i> Đóng góp ý kiến đến ban quản lý nhà trường</h1>
                <p>Trường Đại học Công nghệ Sài Gòn</p>
            </div>
            <ul class="app-breadcrumb breadcrumb">
                <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
                <li class="breadcrumb-item">Trang chủ</li>
                <li class="breadcrumb-item"><a href="#">Trang góp ý</a></li>
            </ul>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="tile">
                    <div class="row">
                        <div class="col-lg-12">
                        <form action="comment" method="POST">
                                <div class="form-group row">
                                    <div class="col-md-3">
                                        <label for="exampleText">Chủ đề ý kiến</label>
                                    </div>
                                    <div class="col-md-8">
                                    <input class="form-control" id="title" type="text" placeholder="Nhập vào chủ đề" name="_token" value="{{csrf_token()}}"><small class="form-text text-muted" id="emailHelp"></small>
                                    </div>
                                </div>                  
                                <div class="form-group row">
                                    <div class="col-md-3">
                                        <label for="exampleTextarea">Nội dung</label>
                                    </div>
                                    <div class="col-md-8">
                                        <textarea class="form-control" id="content" rows="5"></textarea>
                                    </div>
                                </div>                                                
                            </form>
                        </div>           
                    </div>
                    <div class="tile-footer">
                        <button class="btn btn-primary" type="submit">Gửi</button>
                        <button class="btn btn-danger" type="cancel">Hủy</button>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
