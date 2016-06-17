@extends('inoplate-account::auth.layout')
@inject('captcha', 'Inoplate\Captcha\Challenge')

@php($title = trans('inoplate-account::labels.auth.register'))
@php($pageType = $boxType = $logoType = 'register')

@push('header-styles-stack')
    <link href="/vendor/inoplate-account/auth/register.css" type="text/css" rel="stylesheet" />
    <link href="/vendor/inoplate-captcha/css/captcha.css" type="text/css" rel="stylesheet" />
@endpush

@push('footer-scripts-stack')
    <script src="/vendor/inoplate-captcha/js/captcha.js" type="text/javascript"></script>
@endpush

@section('content')
    <div class="login-box-body">
        <p class="login-box-msg">{{ trans('inoplate-account::messages.auth.register') }}</p>
        <form action="{{ route('account.auth.register.post') }}" method="post">
            <div class="form-group has-feedback {{ $errors->has('username') ? 'has-error' : '' }}">
                <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                <input data-rule-required=true type="text" name="username" class="form-control" value="{{ old('username') }}" placeholder="{{ trans('inoplate-account::labels.username') }}" required/>
                <span class="glyphicon glyphicon-user form-control-feedback"></span>
                @if($errors->has('username'))
                    <label id="identifier-error" class="error" for="identifier">
                        <i class="fa fa-times-circle-o"></i> 
                        {{ $errors->first('username') }}
                    </label>
                @endif
            </div>
            <div class="form-group has-feedback {{ $errors->has('email') ? 'has-error' : '' }}">
                <input data-rule-required=true  data-rule-email=true type="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="{{ trans('inoplate-account::labels.email') }} " required/>
                <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                @if($errors->has('email'))
                    <label id="identifier-error" class="error" for="identifier">
                        <i class="fa fa-times-circle-o"></i> 
                        {{ $errors->first('email') }}
                    </label>
                @endif
            </div>
            <div class="form-group has-feedback {{ $errors->has('name') ? 'has-error' : '' }}">
                <input data-rule-required=true name="name" class="form-control" value="{{ old('name') }}" placeholder="{{ trans('inoplate-account::labels.name') }}" required/>
                <span class="glyphicon glyphicon-bookmark form-control-feedback"></span>
                @if($errors->has('name'))
                    <label id="identifier-error" class="error" for="identifier">
                        <i class="fa fa-times-circle-o"></i> 
                        {{ $errors->first('name') }}
                    </label>
                @endif
            </div>
            <div class="form-group has-feedback {{ $errors->has('password') ? 'has-error' : '' }}">
                <input data-rule-required=true data-rule-minlength={{ config('inoplate.account.password_min_length') }} type="password" name="password" class="form-control" placeholder="{{ trans('inoplate-account::labels.password') }}" required/>
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                @if($errors->has('password'))
                    <label id="identifier-error" class="error" for="identifier">
                        <i class="fa fa-times-circle-o"></i> 
                        {{ $errors->first('password') }}
                    </label>
                @endif
            </div>
            <div class="form-group has-feedback {{ $errors->has('password_confirmation') ? 'has-error' : '' }}">
                <input data-rule-equalto=[name="password"] type="password" name="password_confirmation" class="form-control" placeholder="{{ trans('inoplate-account::labels.password_confirmation') }}"/>
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                @if($errors->has('password_confirmation'))
                    <label id="identifier-error" class="error" for="identifier">
                        <i class="fa fa-times-circle-o"></i> 
                        {{ $errors->first('password_confirmation') }}
                    </label>
                @endif
            </div>
            @if(config('inoplate.account.enable_captcha'))
                {!! $captcha->render() !!}
            @endif
            <div class="row form-group">
                <div class="col-xs-12">
                    <p> {!! trans('inoplate-account::messages.auth.register_term', ['term' => 'asdasd']) !!} </p>
                </div>
                <div class="col-xs-12">
                    <button type="submit" class="btn btn-primary btn-block btn-flat">{{ trans('inoplate-account::labels.auth.register') }}</button>
                </div><!-- /.col -->
            </div>
        </form>
        <p><a href="{{ route('account.auth.login.get') }}"><i class="fa fa-long-arrow-left"></i> {{ trans('inoplate-account::messages.auth.login') }}</a><br></p>
    </div><!-- /.login-box-body -->
@endsection