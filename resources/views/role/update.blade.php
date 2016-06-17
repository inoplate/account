@extends('inoplate-foundation::layouts.default')

@php($title = trans('inoplate-account::labels.roles.update.title'))
@php($subtitle = trans('inoplate-account::labels.roles.update.sub_title'))

@section('content')
    @include('inoplate-foundation::partials.content-header')

    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-info">

                <form class="ajax" method="post" action="{{ route('account.admin.roles.update.put', ['id' => $role['id']]) }}">
                    <input type="hidden" name="_method" value="put" />
                    <div class="box-body">
                      @include('inoplate-account::role.form')
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
        </div>
    </section>
@endsection