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
    @php
        $authCheck = \Illuminate\Support\Facades\Auth::check();
    @endphp
    <header class="app-header"><a class="app-header__logo" href="{{ route('home') }}">STU</a>
        <!-- Sidebar toggle button-->
        <a class="app-sidebar__toggle" href="#" data-toggle="sidebar" aria-label="Hide Sidebar"></a>
        <!-- Navbar Right Menu-->
        <ul class="app-nav">
            <!--Notification Menu-->
            @if($authCheck)
                @if($userLogin->Role->weight != ROLE_ADMIN)
                    <li class="dropdown">
                        <a class="app-nav__item" href="#" data-toggle="dropdown"
                                            aria-label="Show notifications" style="text-decoration: none;">Thông báo &nbsp;<i class="fa fa-bell-o fa-lg"></i><span class="message-notification">{{ count($notifications) }}</span></a>
                        <ul class="app-notification dropdown-menu dropdown-menu-right">
                            <li class="app-notification__title">Bạn có {{ count($notifications) }} thông báo mới</li>
                            <div class="app-notification__content">
                                @foreach($notifications as $key => $value)
                                <li>
                                    <a class="app-notification__item" href="{{ route('notifications','id='.$value->id) }}"><span
                                                class="app-notification__icon"><span class="fa-stack fa-lg"><i
                                                        class="fa fa-circle fa-stack-2x text-primary"></i><i
                                                        class="fa fa-envelope fa-stack-1x fa-inverse"></i></span></span>
                                        <div>
                                            <p class="app-notification__message">{!!  $value->title  !!}</p>
                                            <p class="app-notification__meta">{{ date('H:i d/m/y',strtotime($value->created_at)) }}</p>
                                        </div>
                                    </a>
                                </li>
                                @endforeach
                            </div>
                            <li class="app-notification__footer"><a href="{{ route('notifications') }}">Xem tất cả thông báo.</a></li>
                        </ul>
                    </li>
                @endif
            @endif
            {{--<li class="nav-item active">--}}

            {{--</li>--}}
            <!-- User Menu-->

            <li class="dropdown">
                <a class="app-nav__item" href="#" data-toggle="dropdown"
                                    aria-label="Open Profile Menu"  style="text-decoration: none;">
                    @if($authCheck)
                        Xin chào {{ $userLogin->name }}&nbsp;&nbsp;
                    @endif<i class="fa fa-user fa-lg"></i>
                </a>
                <ul class="dropdown-menu settings-menu dropdown-menu-right">
                    {{-- <li><a class="dropdown-item" href="{{ route('permission-list') }}"><i class="fa fa-cog fa-lg"></i> Settings</a>
                    </li> --}}
                    @if($authCheck)
                    <li><a class="dropdown-item" href="{{ route('personal-information-show',$userLogin->users_id) }}"><i class="fa fa-user fa-lg"></i> Thông tin cá nhân</a>
                    @endif
                    </li>
                    <li>
                        <button data-toggle="modal" data-target="#modalChangePassword" class="dropdown-item">
                            <i class="fa fa-key fa-lg"></i> Đổi mật khẩu
                        </button>
                    </li>

                    <li><a class="dropdown-item" href="{{route('logout')}}"
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fa fa-sign-out fa-lg"></i> Đăng xuất
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
            <li><a class="app-menu__item" href="{{ route('home') }}"><i class="app-menu__icon fa fa-home"
                                                                               aria-hidden="true"></i><span
                            class="app-menu__label">Trang chủ</span></a></li>
            @if($authCheck)
                @if($userLogin->Role->weight != ROLE_ADMIN)
                <li class="treeview">
                <a class="app-menu__item" href="#" data-toggle="treeview">
                    <i class="app-menu__icon fa fa-pencil-square-o"></i><span class="app-menu__label">Đánh giá rèn luyện</span>
                    <i class="treeview-indicator fa fa-angle-right"></i>
                </a>
                <ul class="treeview-menu">
                    @can('can-list-student-transcript')
                        <li>
                            <a class="treeview-item" href="{{ route('transcript') }}"><i class="fa fa-file-text-o" aria-hidden="true"></i>&nbsp; Danh sách sinh viên</a>
                        </li>
                    @endcan
                    @if($authCheck)
                        @if($userLogin->Role->id == 1 OR $userLogin->Role->id == 2)
                        <li><a class="treeview-item" href="{{route('transcript-show',$userLogin->Student->id )}}"><i class="icon fa fa-circle-o"></i>
                                Điểm Cá Nhân</a>
                        </li>
                        @endif
                    @endif
                    @can('manage-remaking')
                    <li>
                        <a class="treeview-item" href="{{ route('remaking') }}"><i class="fa fa-file-text-o" aria-hidden="true"></i>&nbsp;Danh sách yêu cầu phúc khảo</a>
                    </li>
                    @endcan
                    @can('import-discipline')
                        <li>
                            <a class="treeview-item" href="{{ route('discipline') }}"><i class="fa fa-file-text-o" aria-hidden="true"></i> Nhập file kỉ luật</a>
                        </li>
                    @endcan

                    @can('view-academic-score')
                        <li>
                            <a class="treeview-item" href="{{ route('academic-transcript') }}"><i class="fa fa-file-text-o" aria-hidden="true"></i> Bảng điểm</a>
                        </li>
                    @endcan
                </ul>
            </li>
                @endif
            @endif
            @if($authCheck)
                @if($userLogin->Role->weight != ROLE_ADMIN)
                    {{-- cái này là để cho sinh viên và ban cán sự tự quản lí của mình--}}
                    @can('proofs-list')
                        @if($userLogin->Role->weight < ROLE_COVANHOCTAP)
                            <li><a class="app-menu__item" href="{{ route('proof') }}">
                                    <i class="app-menu__icon fa fa-file-text-o" aria-hidden="true"></i><span
                                            class="app-menu__label">Quản lí minh chứng </span>
                                </a>
                            </li>
                        @endif
                    @endcan

                {{-- cái này là để cố vấn, khoa, ctsv xem lại minh chứng và sửa trạng thái cho nhanh--}}
                    @can('proofs-list-student')
                        @if($userLogin->Role->weight >= ROLE_COVANHOCTAP)
                            <li>
                                <a class="app-menu__item" href="{{ route('proof-list') }}">
                                    <i class="app-menu__icon fa fa-file-text-o" aria-hidden="true"></i><span
                                            class="app-menu__label">Quản lí minh chứng SV</span>
                                </a>
                            </li>
                        @endif
                    @endcan
                    @can('comment-add')
                        @if ($userLogin->Role->weight < ROLE_COVANHOCTAP)
                            <li class="treeview"><a class="app-menu__item" href="#" data-toggle="treeview"><i
                                class="app-menu__icon fa fa-text-width"></i><span class="app-menu__label">Góp ý</span><i
                                class="treeview-indicator fa fa-angle-right"></i></a>

                                    <ul class="treeview-menu">
                                        <li><a class="treeview-item" href="{{ route('comment-create') }}"><i
                                                        class="icon fa fa-circle-o"></i> Gửi ý kiến đóng góp</a></li>
                                    </ul>

                                <ul class="treeview-menu">
                                    <li><a class="treeview-item" href="{{ route('comment-list') }}"><i
                                                    class="icon fa fa-circle-o"></i>
                                            Danh sách ý kiến</a></li>
                                </ul>
                            </li>
                        @endif
                    @else
                        <li>
                            <a class="treeview-item" href="{{ route('comment-list') }}">
                                <i class="icon fa fa-comment"></i>Danh sách ý kiến
                            </a>
                        </li>
                    @endcan
                    @can('can-change-news')
                        <li>
                            <a class="app-menu__item" href="{{ route('news') }}">
                                <i class="app-menu__icon fa fa-newspaper-o" aria-hidden="true"></i><span
                                        class="app-menu__label">Quản lí tin tức</span>
                            </a>
                        </li>
                        {{--<li class="treeview"><a class="app-menu__item" href="#" data-toggle="treeview"><i--}}
                                        {{--class="app-menu__icon fa fa-newspaper-o"></i><span class="app-menu__label"> Tin tức</span><i--}}
                                        {{--class="treeview-indicator fa fa-angle-right"></i></a>--}}
                            {{--<ul class="treeview-menu">--}}
                                {{--<li><a class="treeview-item" href="{{ route('news') }}"><i class="icon fa fa-circle-o"></i> Tin tức, sự kiện</a></li>--}}
                            {{--</ul>--}}
                        {{--</li>--}}
                    @endcan

                    @can('semester-change')
                    <li><a class="app-menu__item" href="{{ route('semester-list') }}">
                            <i class="app-menu__icon fa fa-th-list" aria-hidden="true"></i><span
                                    class="app-menu__label">Quản lí học kì </span>
                        </a>
                    </li>
                    @endcan

                    @can('faculty-change')
                    <li><a class="app-menu__item" href="{{ route('faculty') }}">
                            <i class="app-menu__icon fa fa-th-list" aria-hidden="true"></i><span
                                    class="app-menu__label">Quản lí Khoa, Lớp, Sinh viên </span>
                        </a>
                    </li>
                    @endcan

                    {{--@can(array('faculty-list'))--}}
                    {{--<li class="treeview"><a class="app-menu__item" href="#" data-toggle="treeview"><i--}}
                                    {{--class="app-menu__icon fa fa-cogs"></i><span--}}
                                    {{--class="app-menu__label">Quản lí Khoa - Sinh viên</span><i--}}
                                    {{--class="treeview-ind icator fa fa-angle-right"></i></a>--}}
                        {{--<ul class="treeview-menu">--}}
                            {{--@can('faculty-list')--}}
                            {{--<li>--}}
                                {{--<a class="treeview-item" href="{{ route('faculty') }}"><i class="icon fa fa-circle-o"></i> Khoa</a>--}}
                            {{--</li>--}}
                            {{--@endcan--}}
                            {{--@can('student-list')--}}
                                {{--<li><a class="treeview-item" href="{{ route('student') }}"><i class="icon fa fa-circle-o"></i> DS Sinh viên đánh giá</a></li>--}}
                            {{--@endcan--}}
                        {{--</ul>--}}
                    {{--</li>--}}
                    {{--@endif--}}

                    @can('view-list-file-import')
                        <li>
                            <a class="app-menu__item" href="{{ route('files') }}">
                                <i class="app-menu__icon fa fa-list" aria-hidden="true"></i><span
                                        class="app-menu__label">Danh sách file đã nhập</span>
                            </a>
                        </li>
                    @endcan

                        @can('export-file')
                            <li><a class="app-menu__item" href="{{ route('export-file-list') }}">
                                    <i class="app-menu__icon fa fa-download" aria-hidden="true"></i><span
                                            class="app-menu__label">Xuất File đánh giá</span>
                                </a>
                            </li>
                        @endcan

                    @can('backup')
                        <li><a class="app-menu__item" href="{{ route('export-backup') }}">
                                <i class="app-menu__icon fa fa-save" aria-hidden="true"></i><span
                                        class="app-menu__label">Backup</span>
                            </a>
                        </li>
                    @endcan
                @endif
            @endif


            @can('user-rights')
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
            @endcan
            @can('manage-user')
                <li><a class="app-menu__item" href="{{ route('user') }}">
                        <i class="app-menu__icon fa fa-cogs" aria-hidden="true"></i><span
                                class="app-menu__label">Quản lí tài khoản </span>
                    </a>
                </li>
            @endcan

            @can('backup-important')
                <li><a class="app-menu__item" href="{{ route('backup-important') }}">
                        <i class="app-menu__icon fa fa-beer" aria-hidden="true"></i><span
                                class="app-menu__label">BACKUP</span>
                    </a>
                </li>
            @endcan


        </ul>
    </aside>
    <div class="modal fade" id="modalChangePassword" role="dialog">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Đổi mật khẩu</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body">
                    <form id="change-password-form">
                        {!! csrf_field() !!}
                        <div class="form-row">
                            <label for="term">Mật khẩu hiện tại</label>
                            <input type="password" class="form-control current-password" id="current-password" name="current-password" placeholder="Mật khẩu hiện tại">
                        </div>
                        <div class="form-row">
                            <label for="term">Mật khẩu mới</label>
                            <input type="password" class="form-control password" id="password" name="password" placeholder="Mật khẩu mới">
                        </div>
                        <div class="form-row">
                            <label for="term">Nhập lại mật khẩu mới</label>
                            <input type="password" class="form-control password_confirmation" id="password_confirmation" name="password_confirmation" placeholder="Nhập lại mật khẩu mới">
                        </div>

                    </form>
                    <div class="modal-footer">
                        <button data-link="{{ route('change-password') }}" class="btn btn-primary"
                                id="btn-change-password" name="btn-change-password" type="button">
                            Đổi
                        </button>
                        <button class="btn btn-secondary" id="closeForm" type="button" data-dismiss="modal">Đóng
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('javascript')
    <script src="{{ asset('template/js/jquery-3.2.1.min.js') }}"></script>
    <script src="{{ asset('template/js/popper.min.js') }}"></script>
    <script src="{{ asset('template/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('template/js/main.js') }}"></script>
    <script src="{{ asset('template/js/plugins/pace.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap-datepicker.js') }}"></script>
    <script src="{{ asset('vendor/unisharp/laravel-ckeditor/ckeditor.js') }}"></script>
    <script src="{{ asset('template/js/plugins/jquery.dataTables.min.js') }} "></script>
    <script src="{{ asset('template/js/plugins/dataTables.bootstrap.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('template/js/plugins/bootstrap-notify.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('template/js/plugins/sweetalert.min.js') }}"></script>
    <script src="{{ asset('js/script.js') }}"></script>
@stop

@section('sub-javascript')

@stop