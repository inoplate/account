@extends('inoplate-account::auth.layout')

@php($title = trans('inoplate-account::labels.auth.login'))

@section('content')
    <div class="login-box-body">
        <p class="login-box-msg">{{ trans('inoplate-account::messages.auth.login') }}</p>
        <form action="{{ route('account.auth.login.post') }}" method="post">
            <div class="form-group has-feedback {{ $errors->has('identifier') ? 'has-error' : '' }}">
                <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                <input data-rule-required=true name="identifier" class="form-control" value="{{ old('identifier') }}" placeholder="{{ trans('inoplate-account::labels.username_or_email') }}"/>
                <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                @if($errors->has('identifier'))
                    <label id="identifier-error" class="error" for="identifier">
                        <i class="fa fa-times-circle-o"></i> 
                        {{ $errors->first('identifier') }}
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
            <div class="row">
                <div class="col-xs-8">    
                    <div class="checkbox icheck">
                        <label>
                            <input {{ old('remember') !== null ? 'checked="checked"' : '' }} name="remember" type="checkbox"> {{ trans('inoplate-account::labels.auth.remember_me') }}
                        </label>
                    </div>                   
                </div><!-- /.col -->
                <div class="col-xs-4">
                    <button type="submit" class="btn btn-primary btn-block btn-flat">{{ trans('inoplate-account::labels.auth.login') }}</button>
                </div><!-- /.col -->
            </div>
        </form>
        
        @if(config('inoplate.account.allow_register'))
            <a href="{{ route('account.auth.register.get') }}">{{ trans('inoplate-account::messages.auth.register') }} <i class="fa fa-long-arrow-right"></i></a><br>
        @endif
        <a href="{{ route('account.password.email.get') }}"><i class="fa fa-long-arrow-left"></i> {{ trans('inoplate-account::messages.password.forgot_password') }}</a><br>

    </div><!-- /.login-box-body -->
@endsection