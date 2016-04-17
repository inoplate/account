@extends('inoplate-foundation::layouts.default')

{{--*/ $title = trans('inoplate-account::labels.permissions.title') /*--}}
{{--*/ $subtitle = trans('inoplate-account::labels.permissions.sub_title') /*--}}

@include('inoplate-foundation::partials.datatables-component')

@push('footer-scripts-stack')
    <script src="/vendor/inoplate-account/permissions/index.js" type="text/javascript"></script>
@endpush

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