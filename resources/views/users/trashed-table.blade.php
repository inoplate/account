<table id="trashed-users-table" class="table table-bordered table-striped datatable" role="grid" data-actions="{{ json_encode($actions['trashed']) }}" width="100%">
    <thead>
        <th>
            <div class="checkbox icheck">
                <input type="checkbox" name="checkall" />
            </div>
        </th>
        <th>{{ trans('inoplate-account::labels.username') }}</th>
        <th>{{ trans('inoplate-account::labels.email') }}</th>
        <th>{{ trans('inoplate-account::labels.name') }}</th>
        <th>{{ trans('inoplate-account::labels.roles.title') }}</th>
        <th>{{ trans('inoplate-account::labels.users.status') }}</th>
        <th>{{ trans('inoplate-account::labels.users.deleted_at') }}</th>
    </thead>
</table>