<?php
/**
 * Created by PhpStorm.
 * User: Thai Duc
 * Date: 06-Apr-18
 * Time: 8:02 PM
 */
?>

@extends('layouts.default')

@section('content')
    <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class="fa fa-file-text-o"></i> Phiếu đánh giá điểm rèn luyện</h1>
                <p>Trường Đại học Công nghệ Sài Gòn</p>
            </div>
            <ul class="app-breadcrumb breadcrumb side">
                <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
                <li class="breadcrumb-item">Trang chủ</li>
                <li class="breadcrumb-item active"><a href="#">Phiếu đánh giá điểm rèn luyện</a></li>
            </ul>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="tile">
                    <div class="tile-body">

                        <div class="tile user-settings">
                            <h4 class="line-head">Thông tin sinh viên</h4>
                            <div class="row">
                                <div class="col-md-6">
                                    <div>- Họ và tên: Trần Ngọc Gia Hân</div>
                                    <div>- Lớp: D14TH02</div>
                                    <div>- MSSV: DH51401681</div>
                                </div>
                                <div class="col-md-6">
                                    <div>- Khoa: Công nghệ thông tin</div>
                                    <div>- Niên khóa: 2014 - 2018</div>
                                </div>
                            </div>
                        </div>
                        <table class="table table-hover table-bordered">
                            <!-- <table class="table table-hover table-bordered" id="sampleTable"> -->
                            <thead>
                            <tr>
                                <th>Nội dung đánh giá</th>
                                <th>Thang điểm</th>
                                <th>Sinh viên tự đánh giá</th>
                                <th>Tập thể lớp đánh giá</th>
                                <th>CVHT/GVCN kết luận điểm</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>Tiger Nixon</td>
                                <td>System Architect</td>
                                <td>Edinburgh</td>
                                <td>61</td>
                                <td>2011/04/25</td>
                            </tr>
                            <tr>
                                <td>Garrett Winters</td>
                                <td>Accountant</td>
                                <td>Tokyo</td>
                                <td>63</td>
                                <td>2011/07/25</td>
                            </tr>
                            <tr>
                                <td>Ashton Cox</td>
                                <td>Junior Technical Author</td>
                                <td>San Francisco</td>
                                <td>66</td>
                                <td>2009/01/12</td>
                            </tr>
                            <tr>
                                <td>Cedric Kelly</td>
                                <td>Senior Javascript Developer</td>
                                <td>Edinburgh</td>
                                <td>22</td>
                                <td>2012/03/29</td>
                            </tr>
                            <tr>
                                <td>Airi Satou</td>
                                <td>Accountant</td>
                                <td>Tokyo</td>
                                <td>33</td>
                                <td>2008/11/28</td>

                            </tr>
                            <tr>
                                <td>Brielle Williamson</td>
                                <td>Integration Specialist</td>
                                <td>New York</td>
                                <td>61</td>
                                <td>2012/12/02</td>
                            </tr>
                            <tr>
                                <td>Herrod Chandler</td>
                                <td>Sales Assistant</td>
                                <td>San Francisco</td>
                                <td>59</td>
                                <td>2012/08/06</td>
                            </tr>
                            <tr>
                                <td>Rhona Davidson</td>
                                <td>Integration Specialist</td>
                                <td>Tokyo</td>
                                <td>55</td>
                                <td>2010/10/14</td>
                            </tr>
                            <tr>
                                <td>Colleen Hurst</td>
                                <td>Javascript Developer</td>
                                <td>San Francisco</td>
                                <td>39</td>
                                <td>2009/09/15</td>
                            </tr>
                            <tr>
                                <td>Sonya Frost</td>
                                <td>Software Engineer</td>
                                <td>Edinburgh</td>
                                <td>23</td>
                                <td>2008/12/13</td>
                            </tr>
                            <tr>
                                <td>Jena Gaines</td>
                                <td>Office Manager</td>
                                <td>London</td>
                                <td>30</td>
                                <td>2008/12/19</td>
                            </tr>
                            <tr>
                                <td>Quinn Flynn</td>
                                <td>Support Lead</td>
                                <td>Edinburgh</td>
                                <td>22</td>
                                <td>2013/03/03</td>
                            </tr>
                            <tr>
                                <td>Charde Marshall</td>
                                <td>Regional Director</td>
                                <td>San Francisco</td>
                                <td>36</td>
                                <td>2008/10/16</td>
                            </tr>
                            <tr>
                                <td>Haley Kennedy</td>
                                <td>Senior Marketing Designer</td>
                                <td>London</td>
                                <td>43</td>
                                <td>2012/12/18</td>
                            </tr>
                            <tr>
                                <td>Tatyana Fitzpatrick</td>
                                <td>Regional Director</td>
                                <td>London</td>
                                <td>19</td>
                                <td>2010/03/17</td>
                            </tr>
                            <tr>
                                <td>Michael Silva</td>
                                <td>Marketing Designer</td>
                                <td>London</td>
                                <td>66</td>
                                <td>2012/11/27</td>
                            </tr>
                            <tr>
                                <td>Paul Byrd</td>
                                <td>Chief Financial Officer (CFO)</td>
                                <td>New York</td>
                                <td>64</td>
                                <td>2010/06/09</td>
                            </tr>
                            <tr>
                                <td>Gloria Little</td>
                                <td>Systems Administrator</td>
                                <td>New York</td>
                                <td>59</td>
                                <td>2009/04/10</td>
                            </tr>
                            <tr>
                                <td>Bradley Greer</td>
                                <td>Software Engineer</td>
                                <td>London</td>
                                <td>41</td>
                                <td>2012/10/13</td>
                            </tr>
                            <tr>
                                <td>Dai Rios</td>
                                <td>Personnel Lead</td>
                                <td>Edinburgh</td>
                                <td>35</td>
                                <td>2012/09/26</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>

@endsection

@section('sub-javascript')

    <!-- Page specific javascripts-->
    <!-- Data table plugin-->
{{--    <script type="text/javascript" src="{{ asset('template/js/plugins/jquery.dataTables.min.js') }} "></script>--}}
{{--    <script type="text/javascript" src="{{ asset('template/js/plugins/dataTables.bootstrap.min.js') }}"></script>--}}
    {{--<script type="text/javascript">$('#sampleTable').DataTable();</script>--}}
    <!-- Google analytics script-->
    <script type="text/javascript">
//        if (document.location.hostname == 'pratikborsadiya.in') {
//            (function (i, s, o, g, r, a, m) {
//                i['GoogleAnalyticsObject'] = r;
//                i[r] = i[r] || function () {
//                        (i[r].q = i[r].q || []).push(arguments)
//                    }, i[r].l = 1 * new Date();
//                a = s.createElement(o),
//                    m = s.getElementsByTagName(o)[0];
//                a.async = 1;
//                a.src = g;
//                m.parentNode.insertBefore(a, m)
//            })(window, document, 'script', '//www.google-analytics.com/analytics.js', 'ga');
//            ga('create', 'UA-72504830-1', 'auto');
//            ga('send', 'pageview');
//        }
    </script>
@endsection