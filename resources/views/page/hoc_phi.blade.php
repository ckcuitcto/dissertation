@extends('master')
@section('content')
<div class="app-title">
        <div>
          <h1><i class="fa fa-file-text-o"></i> Học Phí</h1>
          <p>Trường Đại học Công nghệ Sài Gòn</p>
        </div>
        <ul class="app-breadcrumb breadcrumb">
          <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
          <li class="breadcrumb-item"><a href="#">Xem học phí</a></li>
        </ul>
      </div>
      <div class="row">
        <div class="col-md-12">
          <div class="tile">
            <section class="invoice">
              <div class="row mb-4">
                <div class="col-6">
                  <h2 class="page-header"><i class="fa fa-globe"></i> Xem học phí</h2>
                </div>
                <div class="col-6">
                  <h5 class="text-right">Học Kỳ 2 - Năm học 2017-2018</h5>
                </div>
              </div>
              <div class="row invoice-info">
                <div class="col-2"></div>
                <div class="col-4" style="border:solid 1px;box-shadow: 5px 10px #888888;">
                  <div>Họ và tên: Trần Ngọc Gia Hân</div>
                  <div>Lớp: D14-TH02</div>
                  <div>MSSV: DH51401681</div>
                  <div>Ngày sinh: 26/03/1996</div>
                  <div>Nơi sinh: Long An</div>
                </div>
                <div class="col-4" style="border:solid 1px;box-shadow: 5px 10px #888888;">
                  <div>Khoa: Công nghệ Thông tin</div>
                  <div>Hệ đào tạo: Đại học chính quy</div>
                  <div>Niên khóa: 2014-2018</div>
                  <div>CVHT: Lương An Vinh</div>
                </div>                
              </div>
              <br>
              <div class="row">
                <div class="col-12 table-responsive">
                  <table class="table table-striped">
                    <thead>
                      <tr>
                        <th>STT</th>
                        <th>Mã Môn Học</th>
                        <th>Tên Môn Học</th>
                        <th>Số TC</th>
                        <th>Số HP</th>
                        <th>Số Tiền</th>
                        <th>Ghi Chú</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td>1</td>
                        <td>1THWECN009</td>
                        <td>Xây dựng phần mềm web</td>
                        <td>3</td>
                        <td>3.0</td>
                        <td>1.140.000</td>
                        <td></td>
                      </tr>  
                      <tr>
                          <td>1</td>
                          <td>1THWECN009</td>
                          <td>Lý luận chính trị cuối khóa</td>
                          <td>3</td>
                          <td>3.0</td>
                          <td>1.140.000</td>
                          <td></td>
                        </tr>  
                        <tr>
                            <td>1</td>
                            <td>1THWECN009</td>
                            <td>Thực tập tốt nghiệp</td>
                            <td>3</td>
                            <td>3.0</td>
                            <td>1.140.000</td>
                            <td></td>
                          </tr>                     
                    </tbody>
                  </table>
                </div>
              </div>
              
                <div>Tổng số tín chỉ đăng ký: 8</div>
                <div>Tổng số tín chỉ đăng ký: 8</div>
                <div>Học phí học kỳ: 4.505.000 đồng</div>
                <div>Nợ học kỳ cũ: 0 đồng</div>
                <div>Số tiền đã đóng: 0 đồng</div>
                <div>Số tiền còn nợ: 4.505.000 đồng</div>
            </section>
          </div>
        </div>
      </div>
@endsection