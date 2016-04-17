@inject('authis', 'Roseffendi\Authis\Authis')

<table id="permissions-table" class="table table-bordered table-striped datatable" role="grid" width="100%">
    <thead>
        <th>{{ trans('inoplate-account::labels.permissions.table.pivot') }}</th>
        @foreach($roles as $role)
            <th>{{ $role->name()->value() }}</th>
        @endforeach
    </thead>
    <tbody>
        @foreach($permissions as $permission)
            <tr>
                <td>{{ trans($permission->description()->value()['description']) }}</td>
                @foreach($roles as $role)
                    @if(in_array($permission, $role->permissions()))
                        @if($authis->check('account.admin.permissions.update.put'))
                            <td>
                                <form action="/admin/inoplate-account/permission/{{ $role->id()->value() }}/{{ $permission->id()->value() }}" class="ajax" method="post">
                                    <input type="hidden" name="_method" value="put" />
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                                    <input type="checkbox" checked="checked" name="attached" />
                                </form>
                            </td>
                        @else
                            <td><i class="fa fa-check text-green"></i></td>
                        @endif
                    @else
                        @if($authis->check('account.admin.permissions.update.put'))
                            <td>
                                <form action="/admin/inoplate-account/permission/{{ $role->id()->value() }}/{{ $permission->id()->value() }}" class="ajax" method="post">
                                    <input type="hidden" name="_method" value="put" />
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                                    <input type="checkbox" name="attached" />
                                </form>
                            </td>
                        @else
                            <td><i class="fa fa-minus"></i> </td>
                        @endif
                    @endif
                @endforeach
            </tr>
        @endforeach
    </tbody>
</table>