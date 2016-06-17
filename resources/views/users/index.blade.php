@extends('inoplate-foundation::layouts.default')

@php($title = trans('inoplate-account::labels.users.title'))
@php($subtitle = trans('inoplate-account::labels.users.sub_title'))

@addAsset('datatables')

@section('content')
    @include('inoplate-foundation::partials.content-header')

    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#active-users" data-toggle="tab">{{ trans('inoplate-foundation::labels.active') }}</a></li>
                        <li><a href="#trashed-users" data-toggle="tab" class="text-red"> <i class="fa fa-trash"></i> {{ trans('inoplate-foundation::labels.trashed') }}</a></li>
                    </ul>
                    <div class="box-filter with-border">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        {{ trans('inoplate-account::labels.roles.title') }}
                                    </span>
                                    <select class="form-control" name="roles[]" multiple="multiple" data-placeholder="{{ trans('inoplate-account::labels.users.form.roles') }}">
                                        @foreach($roles as $role)
                                            <option value="{{ $role->id()->value() }}">{{ $role->name()->value() }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        {{ trans('inoplate-account::labels.users.status') }}
                                    </span>
                                    <select class="form-control" name="active">
                                        <option value="">{{ trans('inoplate-foundation::labels.form.no_filter') }}</option>
                                        <option value="1">{{ trans('inoplate-account::labels.users.form.status.active') }}</option>
                                        <option value="0">{{ trans('inoplate-account::labels.users.form.status.inactive') }}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-content">
                        <div class="tab-pane active" id="active-users">
                            <div class="table-responsive">
                                @include('inoplate-account::users.active-table')
                            </div>
                        </div>
                        <div class="tab-pane" id="trashed-users">
                            <div class="table-responsive">
                                @include('inoplate-account::users.trashed-table')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@addJs(['vendor/inoplate-account/users/index.js'])