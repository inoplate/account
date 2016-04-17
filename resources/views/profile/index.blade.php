@extends('inoplate-foundation::layouts.default')

{{--*/ $title = trans('inoplate-account::labels.profile.title') /*--}}
{{--*/ $subtitle = trans('inoplate-account::labels.profile.sub_title') /*--}}

@section('content')
    @include('inoplate-foundation::partials.content-header')

    <section class="content">
        <div class="row">
            <div class="col-md-8">
                <div class="box box-solid">

                <form class="ajax" method="post" action="/admin/profile">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                    <input type="hidden" name="_method" value="put" />
                    <div class="box-body">
                      @include('inoplate-account::profile.form')
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