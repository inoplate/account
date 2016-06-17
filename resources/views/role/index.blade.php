@extends('inoplate-foundation::layouts.default')

@php($title = trans('inoplate-account::labels.roles.title'))
@php($subtitle = trans('inoplate-account::labels.roles.sub_title'))

@addAsset('datatables')
@addCss('vendor/inoplate-account/role/index.css')
@addJs('vendor/inoplate-account/role/index.js')

@section('content')
    @include('inoplate-foundation::partials.content-header')

    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#active-roles" data-toggle="tab">{{ trans('inoplate-foundation::labels.active') }}</a></li>
                        <li><a href="#trashed-roles" data-toggle="tab" class="text-red"> <i class="fa fa-trash"></i> {{ trans('inoplate-foundation::labels.trashed') }}</a></li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="active-roles">
                            <div class="table-responsive">
                                @include('inoplate-account::role.active-table')
                            </div>
                        </div>
                        <div class="tab-pane" id="trashed-roles">
                            <div class="table-responsive">
                                @include('inoplate-account::role.trashed-table')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection