@extends('inoplate-foundation::layouts.default')

@php($title = trans('inoplate-account::labels.users.register.title'))
@php($subtitle = trans('inoplate-account::labels.users.register.sub_title'))

@addJs('vendor/inoplate-account/users/register.js')

@section('content')
    @include('inoplate-foundation::partials.content-header')

    <section class="content">
        <div class="row">
            <div class="col-md-8">
                <div class="box box-info">

                <form class="ajax" method="post" action="/admin/inoplate-account/users/register" id="user-registration-form">
                    <div class="box-body">
                      <input type="hidden" name="avatar">
                      @include('inoplate-account::users.form')
                      <div class="form-group">
                        <label for="password" class="control-label">{{ trans('inoplate-account::labels.password') }}</label>
                        <input type="password" name="password" id="password" data-rule-required=true data-rule-minlength={{ config('inoplate.account.password_min_length') }} class="form-control" placeholder="{{ trans('inoplate-account::labels.password') }}">
                        @include('inoplate-adminutes::partials.form-error', ['field' => 'password'])
                      </div>
                      <div class="form-group">
                        <label for="password_confirmation" class="control-label">{{ trans('inoplate-account::labels.password_confirmation') }}</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" data-rule-equalto=[name="password"] placeholder="{{ trans('inoplate-account::labels.password_confirmation') }}">
                        @include('inoplate-adminutes::partials.form-error', ['field' => 'password_confirmation'])
                      </div>
                    </div>
                    <div class="box-footer">
                        <button type="submit" class="btn btn-info pull-right">{{ trans('inoplate-foundation::labels.form.save') }}</button>
                    </div>
                </form>
                <div class="overlay hide">
                    <div class="loading">Loading..</div>
                </div>
              </div>
            </div>
            <div class="col-md-4">
                @include('inoplate-account::widgets.user-card')
            </div>
        </div>
    </section>
@endsection