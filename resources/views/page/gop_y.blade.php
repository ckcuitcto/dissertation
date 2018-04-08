@extends('master')
@section('content')
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
                <form>
                  <div class="form-group row">
                    <div class="col-md-3">
                        <label for="exampleText">Chủ đề ý kiến</label>
                    </div>
                    <div class="col-md-8">
                        <input class="form-control" id="exampleText" type="text" placeholder="Nhập vào chủ đề"><small class="form-text text-muted" id="emailHelp"></small>
                    </div>
                  </div>
                  <!-- <div class="form-group">
                    <label for="exampleInputPassword1">Password</label>
                    <input class="form-control" id="exampleInputPassword1" type="password" placeholder="Password">
                  </div> -->
                  <div class="form-group row">
                    <div class="col-md-3">
                        <label for="exampleSelect1">Phân loại ý kiến</label>
                    </div>
                    <div class="col-md-8">
                        <select class="form-control" id="exampleSelect1">
                            <option value=""></option>
                            <option>Cán bộ nhân viên / Giảng viên</option>
                            <option>Sinh viên</option>
                          </select>
                    </div>
                  </div>
                  <!-- <div class="form-group">
                    <label for="exampleSelect2">Example multiple select</label>
                    <select class="form-control" id="exampleSelect2" multiple="">
                      <option>1</option>
                      <option>2</option>
                      <option>3</option>
                      <option>4</option>
                      <option>5</option>
                    </select>
                  </div> -->
                  <div class="form-group row">
                    <div class="col-md-3">
                        <label for="exampleTextarea">Nội dung</label>
                    </div>
                    <div class="col-md-8">
                        <textarea class="form-control" id="exampleTextarea" rows="5"></textarea>
                    </div>
                  </div>
                  <div class="form-group">
                      <!-- <label for="exampleInputFile">File input</label> -->
                        <input class="form-control-file" id="exampleInputFile" type="file" aria-describedby="fileHelp">
                        <small class="form-text text-muted" id="fileHelp">Đính kèm tệp hoặc văn bản để góp ý.</small>
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
@endsection