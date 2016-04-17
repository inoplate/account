<table id="trashed-table" class="table table-bordered table-striped datatable" role="grid" data-actions="{{ json_encode($actions['trashed']) }}" width="100%">
    <thead>
        <th>
            <div class="checkbox icheck">
                <input type="checkbox" name="checkall" />
            </div>
        </th>
        <th>{{ trans('inoplate-foundation::labels.name') }}</th>
        <th>{{ trans('inoplate-foundation::labels.slug') }}</th>
        <th>{{ trans('inoplate-foundation::labels.deleted_at') }}</th>
    </thead>
</table>