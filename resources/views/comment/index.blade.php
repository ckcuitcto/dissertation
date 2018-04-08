<?php
/**
 * Created by PhpStorm.
 * User: Thai Duc
 * Date: 06-Apr-18
 * Time: 9:31 PM
 */
?>
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
                                <!-- <fieldset class="form-group">
                                  <legend>Radio buttons</legend>
                                  <div class="form-check">
                                    <label class="form-check-label">
                                      <input class="form-check-input" id="optionsRadios1" type="radio" name="optionsRadios" value="option1" checked="">Option one is this and that—be sure to include why it's great
                                    </label>
                                  </div>
                                  <div class="form-check">
                                    <label class="form-check-label">
                                      <input class="form-check-input" id="optionsRadios2" type="radio" name="optionsRadios" value="option2">Option two can be something else and selecting it will deselect option one
                                    </label>
                                  </div>
                                  <div class="form-check disabled">
                                    <label class="form-check-label">
                                      <input class="form-check-input" id="optionsRadios3" type="radio" name="optionsRadios" value="option3" disabled="">Option three is disabled
                                    </label>
                                  </div>
                                </fieldset> -->
                                <!-- <div class="form-check">
                                  <label class="form-check-label">
                                    <input class="form-check-input" type="checkbox">Check me out
                                  </label>
                                </div> -->
                            </form>
                        </div>
                        <!-- <div class="col-lg-4 offset-lg-1">
                          <form>
                            <div class="form-group has-success">
                              <label class="form-control-label" for="inputSuccess1">Valid input</label>
                              <input class="form-control is-valid" id="inputValid" type="text">
                              <div class="form-control-feedback">Success! You've done it.</div>
                            </div>
                            <div class="form-group has-danger">
                              <label class="form-control-label" for="inputDanger1">Invalid input</label>
                              <input class="form-control is-invalid" id="inputInvalid" type="text">
                              <div class="form-control-feedback">Sorry, that username's taken. Try another?</div>
                            </div>
                          </form>
                        </div> -->
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
