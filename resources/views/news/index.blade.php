@extends('layouts.default')

@section('content')
<main class="app-content">
<div class="app-title">
        <div>
          <h1><i class="fa fa-laptop"></i> Quản lý tin tức, sự kiện</h1>
          <p>Trường Đại học Công nghệ Sài Gòn</p>
        </div>
        <ul class="app-breadcrumb breadcrumb">
          <li class="breadcrumb-item"><a href="#"><i class="fa fa-home fa-lg"></i></a></li>
          <li class="breadcrumb-item"><a href="#"> Quản lý tin tức, sự kiện</a></li>
        </ul>
      </div>
        <div class="row">
          <div class="col-md-12">
            <div class="tile">
              <table class="table">
                <thead>
                  <tr>
                    <th scope="col">STT</th>
                    <th scope="col">Tiêu đề</th>
                    <th scope="col">Nội dung</th>
                    <th scope="col">Ngày tạo</th>
                    <th scope="col">Ngày cập nhật</th>                    
                    <th>Tác vụ</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($newsList as $tintuc)
                  <tr>
                    <th scope="row">1</th>
                    <td>{{$tintuc->$title}}</td>
                    <td>{{$tintuc->$content}}</td>
                    <td>{{$tintuc->$created_at}}</td>
                    <td>{{$tintuc->$updated_at}}</td>
                    <td>                        
                        <button type="button" class="btn btn-secondary">Sửa</button>          
                        <button type="button" class="btn btn-danger">Xóa</button>
                      </td> 
                  </tr>
                  <tr>
                    <th scope="row">1</th>
                    <td>{{$tintuc->$title}}</td>
                    <td>{{$tintuc->$content}}</td>
                    <td>{{$tintuc->$created_at}}</td>
                    <td>{{$tintuc->$updated_at}}</td>                    
                    <td>                        
                        <button type="button" class="btn btn-secondary">Sửa</button>          
                        <button type="button" class="btn btn-danger">Xóa</button>
                      </td>
                  </tr>
                  <tr>
                    <th scope="row">1</th>
                    <td>{{$tintuc->$title}}</td>
                    <td>{{$tintuc->$content}}</td>
                    <td>{{$tintuc->$created_at}}</td>
                    <td>{{$tintuc->$updated_at}}</td>
                    <td>                        
                        <button type="button" class="btn btn-secondary">Sửa</button>          
                        <button type="button" class="btn btn-danger">Xóa</button>
                      </td>
                  </tr>
                  <tr>
                    <th scope="row">1</th>
                    <td>{{$tintuc->$title}}</td>
                    <td>{{$tintuc->$content}}</td>
                    <td>{{$tintuc->$created_at}}</td>
                    <td>{{$tintuc->$updated_at}}</td>
                    <td>                        
                        <button type="button" class="btn btn-secondary">Sửa</button>                   
                        <button type="button" class="btn btn-danger">Xóa</button>
                    </td>
                  </tr>
                  <tr>
                    <th scope="row">1</th>
                    <td>{{$tintuc->$title}}</td>
                    <td>{{$tintuc->$content}}</td>
                    <td>{{$tintuc->$created_at}}</td>
                    <td>{{$tintuc->$updated_at}}</td>
                    <td>                      
                      <button type="button" class="btn btn-secondary">Sửa</button>                      
                      <button type="button" class="btn btn-danger">Xóa</button>
                    </td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
              <button type="button" class="btn btn-primary">Thêm</button>
            </div>
          </div>
        </div>
      </main>
@endsection