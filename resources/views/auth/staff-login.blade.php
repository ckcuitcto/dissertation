@extends('layouts.master')
@section('content')
    <section class="material-half-bg">
        <div class="cover"></div>
    </section>
    <section class="login-content">
        <div class="logo">
            <h1>Trường Đại học Công nghệ Sài Gòn</h1>
        </div>
        <div class="login-box">
            <form class="login-form" method="POST" role="form" action="{{ route('staff.login.submit') }}">
                {{ csrf_field() }}
                <h3 class="login-head"><i class="fa fa-lg fa-fw fa-user"></i>Đăng nhập</h3>
                <div class="form-group {{ $errors->has('id') ? ' has-error' : '' }}">
                    <label class="control-label">Mã số nhân viên</label>
                    <input class="form-control" type="text" placeholder="MSSV" id="id" name="id" value="{{ old('id') }}" required autofocus>
                    @if ($errors->has('id'))
                        <strong>{{ $errors->first('id') }}</strong>
                    @endif
                </div>

                <div class="form-group {{ $errors->has('password') ? ' has-error' : '' }}">
                    <label for="password" class="ontrol-label">Mật khẩu</label>
                    <input id="password" type="password" class="form-control" name="password" required>
                    @if ($errors->has('password'))
                        <span class="help-block">
                            <strong>{{ $errors->first('password') }}</strong>
                        </span>
                    @endif
                </div>

                <div class="form-group">
                    <div class="utility">
                        <div class="animated-checkbox">
                            <label>
                                <input type="checkbox" name="remember"><span class="label-text" {{ old('remember') ? 'checked' : '' }}>Ghi nhớ</span>
                            </label>
                        </div>
                        <p class="semibold-text mb-2"><a href="#" data-toggle="flip">Quên mật khẩu ?</a></p>
                    </div>
                </div>

                <div class="form-group btn-container">
                    <button type="submit" class="btn btn-primary btn-block"><i class="fa fa-sign-in fa-lg fa-fw"></i>Đăng nhập</button>
                </div>
            </form>

            <form class="forget-form" method="POST" action="{{ route('password.email') }}">
                {{ csrf_field() }}
                <h3 class="login-head"><i class="fa fa-lg fa-fw fa-lock"></i>Quên mật khẩu ?</h3>
                <div class="form-group">
                    <label class="control-label">Email</label>
                    <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required>
                    @if ($errors->has('email'))
                        <span class="help-block">
                            <strong>{{ $errors->first('email') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="form-group btn-container">
                    <button type="submit" class="btn btn-primary btn-block"><i class="fa fa-unlock fa-lg fa-fw"></i>Gửi liên kết khôi phục</button>
                </div>
                <div class="form-group mt-3">
                    <p class="semibold-text mb-0"><a href="#" data-toggle="flip"><i class="fa fa-angle-left fa-fw"></i> Trở về đăng nhập</a></p>
                </div>
            </form>
        </div>
    </section>
@endsection

@section('sub-javascript')
    <script src="{{URL::asset('template/js/plugins/pace.min.js')}}"></script>
    <script type="text/javascript">
        // Login Page Flipbox control
        $('.login-content [data-toggle="flip"]').click(function () {
            $('.login-box').toggleClass('flipped');
            return false;
        });
    </script>
@endsection