@extends('inoplate-foundation::layouts.default')

{{--*/ $title = trans('inoplate-account::labels.roles.create.title') /*--}}
{{--*/ $subtitle = trans('inoplate-account::labels.roles.create.sub_title') /*--}}

@push('footer-scripts-stack')
    <script src="/vendor/inoplate-account/role/create.js" type="text/javascript"></script>
@endpush

@section('content')
    @include('inoplate-foundation::partials.content-header')
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-info">

                <form class="ajax" method="post" action="/admin/inoplate-account/roles/create" id="role-create-form">
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