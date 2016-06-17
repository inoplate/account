@extends('inoplate-foundation::layouts.default')

@php($title = trans('inoplate-account::labels.permissions.title'))
@php($subtitle = trans('inoplate-account::labels.permissions.sub_title'))

@addAsset('datatables')
@addJs('vendor/inoplate-account/permissions/index.js')

@section('content')
    @include('inoplate-foundation::partials.content-header')

    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-info">
                    <div class="box-body">
                        <div class="table-responsive">
                            @include('inoplate-account::permissions.table')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection