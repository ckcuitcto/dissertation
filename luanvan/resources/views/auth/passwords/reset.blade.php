
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
        <div class="login-box form-reset-password">
            <form class="reset-form" method="POST" action="{{ route('password.request') }}">
                {{ csrf_field() }}
                <input type="hidden" name="token" value="{{ $token }}">
                <img src="{{asset('template/assets/logo.bmp')}}" width="30%">
                <div class="row" style="margin-top:-80px;">
                    <div class="col-sm-6 col-md-4"></div>
                    <div class="col-sm-6 col-md-8">
                        <h4 class="login-head" style="text-align:right;color:#006BB3;font-family: 'Dosis', sans-serif;">                    
                            SAIGON TECHNOLOGY UNIVERSITY</h4>
                    </div>
                </div>       
                <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                        <label for="email" class="control-label">Nhập Email</label>
                        <input id="email" type="email" class="form-control" name="email" value="{{ $email or old('email') }}" required autofocus>
                        @if ($errors->has('email'))
                            <span class="help-block">
                                <span class="messageErrors" style="color:red">{{ $errors->first('email') }}</span>
                            </span>
                        @endif
                </div>

                <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                    <label for="password" class="control-label">Password</label>
                    <input id="password" type="password" class="form-control" name="password" required>
                    @if ($errors->has('password'))
                        <span class="help-block">
                            <span class="messageErrors" style="color:red">{{ $errors->first('password') }}</span>
                        </span>
                    @endif
                </div>

                <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                    <label for="password-confirm" class="control-label">Confirm Password</label>
                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                    @if ($errors->has('password_confirmation'))
                        <span class="help-block">
                             <span class="messageErrors" style="color:red">{{ $errors->first('password_confirmation') }}</span>
                        </span>
                    @endif
                </div>
                <div class="form-group btn-container">
                        <button type="submit" class="btn btn-primary btn-block">Đặt lại mật khẩu</button>
                </div>
            </form>
                
        </div>
@endsection
