@extends('inoplate-foundation::layouts.default')

@php($title = trans('inoplate-account::labels.dashboard.title'))

@section('content')
    @include('inoplate-foundation::partials.content-header')

    <section class="content">
        <div class="row">
            <div class="col-md-5">
                {!! $widgets->render('inoplate.account.admin.dashboard.left') !!}
            </div>
            <div class="col-md-7">
            </div>
        </div>
    </section>
@endsection