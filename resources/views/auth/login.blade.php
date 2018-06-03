@extends('layouts.default')
@section('header')
@endsection
@section('menuLeft')
@endsection
@section('content')
    <section class="material-half-bg">
        <div class="cover"></div>
    </section>
    <section class="login-content">
        {{-- <div class="logo">
            <h1>Trường Đại học Công nghệ Sài Gòn</h1>
        </div> --}}
        <div class="login-box">
            <form class="login-form" method="POST" action="{{ route('login') }}">
                {{ csrf_field() }}
                <img src="{{'template/assets/logo.bmp'}}" width="30%">
                <div class="row" style="margin-top:-65px;">
                    <div class="col-sm-6 col-md-4"></div>
                    <div class="col-sm-6 col-md-8">
                        <h4 class="login-head" style="text-align:right;color:#006BB3;font-family: 'Dosis', sans-serif;">                    
                         SAIGON TECHNOLOGY UNIVERSITY</h4>
                    </div>
                </div>              
                                 
                <div class="form-group {{ $errors->has('users_id') ? ' has-error' : '' }}">
                    <label class="control-label">MSSV</label>
                    <input class="form-control" type="text" placeholder="Mã số sinh viên" id="users_id" name="users_id" value="{{ old('users_id') }}" required autofocus>
                    @if ($errors->has('users_id'))
                            <strong>{{ $errors->first('users_id') }}</strong>
                    @endif
                </div>

                <div class="form-group {{ $errors->has('password') ? ' has-error' : '' }}">
                    <label for="password" class="ontrol-label">Password</label>
                    <input id="password" type="password" placeholder="Mật khẩu" class="form-control" name="password" required>
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
                    <button type="submit" class="btn btn-info btn-block"><i class="fa fa-sign-in fa-lg fa-fw"></i>Đăng nhập</button>
                </div>
            </form>

            <form class="forget-form" method="POST" action="{{ route('password.email') }}">
                {{ csrf_field() }}
                <img src="{{'template/assets/logo.bmp'}}" width="30%">
                <div class="row" style="margin-top:-65px;">
                    <div class="col-sm-6 col-md-4"></div>
                    <div class="col-sm-6 col-md-8">
                        <h4 class="login-head" style="text-align:right;color:#006BB3;font-family: 'Dosis', sans-serif;">                    
                         SAIGON TECHNOLOGY UNIVERSITY</h4>
                    </div>
                </div>  
                <h4 class="login-head"><i class="fa fa-lg fa-fw fa-lock"></i>Quên mật khẩu ?</h4>
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
                    <button type="submit" class="btn btn-info btn-block"><i class="fa fa-unlock fa-lg fa-fw"></i>Gửi liên kết khôi phục</button>
                </div>
                <div class="form-group mt-3">
                    <p class="semibold-text mb-0"><a href="#" data-toggle="flip"><i class="fa fa-angle-left fa-fw"></i> Trở về đăng nhập</a></p>
                </div>
            </form>
        </div>
    </section>
@endsection

@section('sub-javascript')
    <script type="text/javascript">
        // Login Page Flipbox control
        $('.login-content [data-toggle="flip"]').click(function () {
            $('.login-box').toggleClass('flipped');
            return false;
        });
    </script>
@endsection