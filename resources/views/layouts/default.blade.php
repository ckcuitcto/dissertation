@extends('layouts.master')

@section('title')
    STU
    <?php
    $urls = explode('/', url()->current());
    $title = str_replace('-', ' ', end($urls));
    echo ' | ' . (URL::to('/') == url()->current() ? 'Home Page' : strtoupper($title));
    ?>
@endsection


@section('header')
    <header class="app-header"><a class="app-header__logo" href="http://www.stu.edu.vn/">STU</a>
        <!-- Sidebar toggle button-->
        <a class="app-sidebar__toggle" href="#" data-toggle="sidebar" aria-label="Hide Sidebar"></a>
        <!-- Navbar Right Menu-->
        <ul class="app-nav">
            <li class="app-search">
                <input class="app-search__input" type="search" placeholder="Search">
                <button class="app-search__button"><i class="fa fa-search"></i></button>
            </li>
            <!--Notification Menu-->
            <li class="dropdown"><a class="app-nav__item" href="#" data-toggle="dropdown"
                                    aria-label="Show notifications"><i class="fa fa-bell-o fa-lg"></i></a>
                <ul class="app-notification dropdown-menu dropdown-menu-right">
                    <li class="app-notification__title">You have 4 new notifications.</li>
                    <div class="app-notification__content">
                        <li><a class="app-notification__item" href="javascript:;"><span
                                        class="app-notification__icon"><span class="fa-stack fa-lg"><i
                                                class="fa fa-circle fa-stack-2x text-primary"></i><i
                                                class="fa fa-envelope fa-stack-1x fa-inverse"></i></span></span>
                                <div>
                                    <p class="app-notification__message">Lisa sent you a mail</p>
                                    <p class="app-notification__meta">2 min ago</p>
                                </div>
                            </a></li>
                        <li><a class="app-notification__item" href="javascript:;"><span
                                        class="app-notification__icon"><span class="fa-stack fa-lg"><i
                                                class="fa fa-circle fa-stack-2x text-danger"></i><i
                                                class="fa fa-hdd-o fa-stack-1x fa-inverse"></i></span></span>
                                <div>
                                    <p class="app-notification__message">Mail server not working</p>
                                    <p class="app-notification__meta">5 min ago</p>
                                </div>
                            </a></li>
                        <li><a class="app-notification__item" href="javascript:;"><span
                                        class="app-notification__icon"><span class="fa-stack fa-lg"><i
                                                class="fa fa-circle fa-stack-2x text-success"></i><i
                                                class="fa fa-money fa-stack-1x fa-inverse"></i></span></span>
                                <div>
                                    <p class="app-notification__message">Transaction complete</p>
                                    <p class="app-notification__meta">2 days ago</p>
                                </div>
                            </a></li>
                        <div class="app-notification__content">
                            <li><a class="app-notification__item" href="javascript:;"><span
                                            class="app-notification__icon"><span class="fa-stack fa-lg"><i
                                                    class="fa fa-circle fa-stack-2x text-primary"></i><i
                                                    class="fa fa-envelope fa-stack-1x fa-inverse"></i></span></span>
                                    <div>
                                        <p class="app-notification__message">Lisa sent you a mail</p>
                                        <p class="app-notification__meta">2 min ago</p>
                                    </div>
                                </a></li>
                            <li><a class="app-notification__item" href="javascript:;"><span
                                            class="app-notification__icon"><span class="fa-stack fa-lg"><i
                                                    class="fa fa-circle fa-stack-2x text-danger"></i><i
                                                    class="fa fa-hdd-o fa-stack-1x fa-inverse"></i></span></span>
                                    <div>
                                        <p class="app-notification__message">Mail server not working</p>
                                        <p class="app-notification__meta">5 min ago</p>
                                    </div>
                                </a></li>
                            <li><a class="app-notification__item" href="javascript:;"><span
                                            class="app-notification__icon"><span class="fa-stack fa-lg"><i
                                                    class="fa fa-circle fa-stack-2x text-success"></i><i
                                                    class="fa fa-money fa-stack-1x fa-inverse"></i></span></span>
                                    <div>
                                        <p class="app-notification__message">Transaction complete</p>
                                        <p class="app-notification__meta">2 days ago</p>
                                    </div>
                                </a></li>
                        </div>
                    </div>
                    <li class="app-notification__footer"><a href="#">See all notifications.</a></li>
                </ul>
            </li>
            <li class="nav-item active">

            </li>
            <!-- User Menu-->

            <li class="dropdown">
                <a class="app-nav__item" href="#" data-toggle="dropdown"
                                    aria-label="Open Profile Menu">
                    @if(\Illuminate\Support\Facades\Auth::check())
                        Hello {{ \Illuminate\Support\Facades\Auth::user()->name }}&nbsp;&nbsp;
                    @endif<i class="fa fa-user fa-lg"></i>
                </a>
                <ul class="dropdown-menu settings-menu dropdown-menu-right">
                    <li><a class="dropdown-item" href="{{ route('home') }}"><i class="fa fa-cog fa-lg"></i> Settings</a>
                    </li>
                    <li><a class="dropdown-item" href="{{ route('home') }}"><i class="fa fa-user fa-lg"></i> Profile</a>
                    </li>


                    <li><a class="dropdown-item" href="{{route('logout')}}"
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fa fa-sign-out fa-lg"></i> Logout
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            {{ csrf_field() }}
                        </form>
                    </li>
                </ul>
            </li>
        </ul>
    </header>
@stop

@section('menuLeft')
    <div class="app-sidebar__overlay" data-toggle="sidebar"></div>
    <aside class="app-sidebar">
        <div class="app-sidebar__user">
            <!-- <img class="app-sidebar__user-avatar" src="https://goo.gl/MK1mfH" alt="User Image"> -->
            <div>
                <p class="app-sidebar__user-name">SAIGON</p>
                <p class="app-sidebar__user-designation"> TECHNOLOGY UNIVERSITY</p>
            </div>
        </div>
        <ul class="app-menu">

            <li><a class="app-menu__item active" href="{{ route('home') }}"><i class="app-menu__icon fa fa-home"
                                                                               aria-hidden="true"></i><span
                            class="app-menu__label">Trang chủ</span></a></li>
            <li class="treeview"><a class="app-menu__item" href="#" data-toggle="treeview"><i
                            class="app-menu__icon fa fa-pencil-square-o"></i><span class="app-menu__label">Đánh giá rèn luyện</span><i
                            class="treeview-indicator fa fa-angle-right"></i></a>
                <ul class="treeview-menu">
                    <li><a class="treeview-item" href="{{ route('evaluation-form') }}"><i
                                    class="icon fa fa-circle-o"></i> Phiếu Đánh Giá</a></li>
                    <li><a class="treeview-item" href="{{route('transcript')}}"><i class="icon fa fa-circle-o"></i> Tổng
                            Điểm Cá Nhân</a></li>
                </ul>
            </li>
            <li><a class="app-menu__item" href="{{ route('personal-information') }}"><i
                            class="app-menu__icon fa fa-user-circle-o"></i><span class="app-menu__label">Thông tin sinh viên</span></a>
            </li>
            <li><a class="app-menu__item" href="{{ route('proofs') }}"><i
                            class="app-menu__icon fa fa-file-text-o"></i><span class="app-menu__label">Quản lí minh chứng</span></a>
            </li>
            @can('can-change-news')
            <li class="treeview"><a class="app-menu__item" href="#" data-toggle="treeview"><i
                            class="app-menu__icon fa fa-newspaper-o"></i><span class="app-menu__label">Tin tức</span><i
                            class="treeview-indicator fa fa-angle-right"></i></a>
                <ul class="treeview-menu">
                    <li><a class="treeview-item" href="{{ route('notification') }}"><i class="icon fa fa-circle-o"></i>
                            Thông Báo</a></li>
                    <li><a class="treeview-item" href="{{ route('news') }}"><i class="icon fa fa-circle-o"></i> Tin Tức,
                            Sự Kiện</a></li>
                </ul>
            </li>
            @endcan

            <li class="treeview"><a class="app-menu__item" href="#" data-toggle="treeview"><i
                            class="app-menu__icon fa fa-cogs"></i><span
                            class="app-menu__label">Quản lí Khoa,Phòng ban</span><i
                            class="treeview-indicator fa fa-angle-right"></i></a>
                <ul class="treeview-menu">
                    <li><a class="treeview-item" href="{{ route('faculty') }}"><i class="icon fa fa-circle-o"></i> Khoa</a>
                    </li>
                    <li><a class="treeview-item" href="{{ route('student') }}"><i class="icon fa fa-circle-o"></i> Sinh
                            viên</a></li>
                    <li><a class="treeview-item" href="{{ route('departmentlist') }}"><i
                                    class="icon fa fa-circle-o"></i> Phòng ban</a></li>
                </ul>
            </li>

            <li class="treeview"><a class="app-menu__item" href="#" data-toggle="treeview"><i
                            class="app-menu__icon fa fa-text-width"></i><span class="app-menu__label">Góp ý</span><i
                            class="treeview-indicator fa fa-angle-right"></i></a>
                @can('comment-add')
                    <ul class="treeview-menu">
                        <li><a class="treeview-item" href="{{ route('comment-create') }}"><i
                                        class="icon fa fa-circle-o"></i> Gửi ý kiến đóng góp</a></li>
                    </ul>
                @endcan
                @can('comment-list')
                    <ul class="treeview-menu">
                        <li><a class="treeview-item" href="{{ route('comment-list') }}"><i
                                        class="icon fa fa-circle-o"></i>
                                Danh sách ý kiến đóng góp</a></li>
                    </ul>
                @endcan
            </li>

            <li class="treeview"><a class="app-menu__item" href="#" data-toggle="treeview"><i
                            class="app-menu__icon fa fa-cog"></i><span class="app-menu__label">Phân quyền User</span><i
                            class="treeview-indicator fa fa-angle-right"></i></a>
                <ul class="treeview-menu">
                    <li><a class="treeview-item" href="{{ route('role-list') }}"><i class="icon fa fa-circle-o"></i>Danh
                            sách các nhóm vai trò </a></li>
                    <li><a class="treeview-item" href="{{ route('permission-list') }}"><i
                                    class="icon fa fa-circle-o"></i> Danh sách các quyền</a></li>
                </ul>
            </li>

            <li class="treeview"><a class="app-menu__item" href="#" data-toggle="treeview"><i
                            class="app-menu__icon fa fa-th-list"></i><span class="app-menu__label">Học kì</span><i
                            class="treeview-indicator fa fa-angle-right"></i></a>
                <ul class="treeview-menu">
                    <li><a class="treeview-item" href="{{ route('semester-list') }}"><i class="icon fa fa-circle-o"></i>Danh
                            sách học kì </a></li>
                </ul>
            </li>

            <li class="treeview"><a class="app-menu__item" href="#" data-toggle="treeview"><i
                            class="app-menu__icon fa fa-calendar"></i><span class="app-menu__label">Hỗ Trợ Học Vụ</span><i
                            class="treeview-indicator fa fa-angle-right"></i></a>
                <ul class="treeview-menu">
                    <li><a class="treeview-item" href="http://daotao1.stu.edu.vn/Default.aspx?page=thoikhoabieu"><i
                                    class="icon fa fa-circle-o"></i> Thời Khóa Biểu</a></li>
                    <li><a class="treeview-item" href="http://daotao1.stu.edu.vn/Default.aspx?page=xemdiemthi"><i
                                    class="icon fa fa-circle-o"></i> Điểm Học Kỳ</a></li>
                    <li><a class="treeview-item" href="http://daotao1.stu.edu.vn/Default.aspx?page=dkmonhoc"><i
                                    class="icon fa fa-circle-o"></i> Đăng ký môn học</a></li>
                    <li><a class="treeview-item" href="http://daotao1.stu.edu.vn/Default.aspx?page=xemhocphi"><i
                                    class="icon fa fa-circle-o"></i> Học Phí</a></li>
                </ul>
            </li>
            <li class="treeview"><a class="app-menu__item" href="#" data-toggle="treeview"><i
                            class="app-menu__icon fa fa-bed"></i><span class="app-menu__label">Các Phòng Ban</span><i
                            class="treeview-indicator fa fa-angle-right"></i></a>
                <ul class="treeview-menu">
                    <li><a class="treeview-item" href="http://www.stu.edu.vn/vi/276/phong-dao-tao.html"><i
                                    class="icon fa fa-circle-o"></i> Phòng Đào Tạo</a></li>
                    <li><a class="treeview-item" href="http://www.stu.edu.vn/vi/280/phong-cong-tac-sinh-vien.html"><i
                                    class="icon fa fa-circle-o"></i> Phòng
                            Công Tác Sinh Viên</a></li>
                    <li><a class="treeview-item" href="http://www.stu.edu.vn/vi/265/khoa-cong-nghe-thong-tin.html"><i
                                    class="icon fa fa-circle-o"></i> Văn Phòng
                            Khoa</a></li>
                </ul>
            </li>
        </ul>
    </aside>
@stop

@section('javascript')
    <script src="{{ URL::asset('template/js/jquery-3.2.1.min.js') }}"></script>
    <script src="{{ URL::asset('template/js/popper.min.js') }}"></script>
    <script src="{{ URL::asset('template/js/bootstrap.min.js') }}"></script>
    <script src="{{ URL::asset('template/js/main.js') }}"></script>
    <script src="{{ URL::asset('template/js/plugins/pace.min.js') }}"></script>
    <script src="{{ URL::asset('js/bootstrap-datepicker.js') }}"></script>
    <script src="{{ asset('vendor/unisharp/laravel-ckeditor/ckeditor.js') }}"></script>
    <script>
        $(document).ready(function () {
            $('#sampleTable').DataTable({
                "language": {
                    "lengthMenu": "Hiển thị _MENU_ bản ghi mỗi trang",
                    "zeroRecords": "Không có bản ghi nào!",
                    "info": "Hiển thị trang _PAGE_ của _PAGES_",
                    "infoEmpty": "Không có bản ghi nào!!!",
                    "infoFiltered": "(Đã lọc từ _MAX_ total bản ghi)",
                },
                "pageLength": 25
            });

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        });

    </script>
@stop

@section('sub-javascript')

@stop