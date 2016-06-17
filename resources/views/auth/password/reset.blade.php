@extends('inoplate-account::auth.layout')
@inject('captcha', 'Inoplate\Captcha\Challenge')

@php($title = trans('inoplate-account::labels.reset'))

@push('header-styles-stack')
    <link href="/vendor/inoplate-captcha/css/captcha.css" type="text/css" rel="stylesheet" />
@endpush

@push('footer-scripts-stack')
    <script src="/vendor/inoplate-captcha/js/captcha.js" type="text/javascript"></script>
@endpush

@section('content')
    <div class="login-box-body">
        <p class="login-box-msg">{{ trans('inoplate-account::messages.password.forgot_password') }}</p>
        <form action="{{ route('account.password.reset.post') }}" method="post">
            <div class="form-group has-feedback {{ $errors->has('email') ? 'has-error' : '' }}">
                <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                <input type="hidden" name="token" value="{{ $token }}" />
                <input data-rule-required=true name="email" class="form-control" value="{{ $email or old('email') }}" placeholder="{{ trans('inoplate-account::labels.email') }}"/>
                <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                @if($errors->has('email'))
                    <label id="identifier-error" class="error" for="identifier">
                        <i class="fa fa-times-circle-o"></i> 
                        {{ $errors->first('email') }}
                    </label>
                @endif
            </div>
            <div class="form-group has-feedback {{ $errors->has('password') ? 'has-error' : '' }}">
                <input data-rule-required=true type="password" name="password" class="form-control" placeholder="{{ trans('inoplate-account::labels.password') }}"/>
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                @if($errors->has('password'))
                    <label id="identifier-error" class="error" for="identifier">
                        <i class="fa fa-times-circle-o"></i> 
                        {{ $errors->first('password') }}
                    </label>
                @endif
            </div>
            <div class="form-group has-feedback {{ $errors->has('password_confirmation') ? 'has-error' : '' }}">
                <input data-rule-required=true  data-rule-equalto=[name="password"] type="password" name="password_confirmation" class="form-control" placeholder="{{ trans('inoplate-
                inoplate-account::labels.password_confirmation') }}"/>
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                @if($errors->has('password_confirmation'))
                    <label id="identifier-error" class="error" for="identifier">
                        <i class="fa fa-times-circle-o"></i> 
                        {{ $errors->first('password_confirmation') }}
                    </label>
                @endif
            </div>
            <div class="row form-group">
                <div class="col-xs-12">
                    <button type="submit" class="btn btn-primary btn-block btn-flat">{{ trans('inoplate-account::labels.reset') }}</button>
                </div><!-- /.col -->
            </div>
        </form>
        <a href="{{ route('account.auth.login.get') }}">{{ trans('inoplate-account::messages.auth.login') }} <i class="fa fa-long-arrow-right"></i></a><br>
    </div><!-- /.login-box-body -->
@endsection