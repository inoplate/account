<table id="active-users-table" class="table table-bordered table-striped datatable" role="grid" data-actions="{{ json_encode($actions['active']) }}" width="100%">
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
        <th>{{ trans('inoplate-account::labels.users.registered_at') }}</th>
        <th>{{ trans('inoplate-foundation::labels.actions') }}</th>
    </thead>
</table>

<div class="modal fade" data-backdrop="static" role="dialog" aria-labelledby="form-modal" id="users-register-form">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"> {{ trans('inoplate-account::labels.users.register.title') }} </h4>
            </div>
            <form class="ajax" method="post" action="/admin/inoplate-account/users/register">
                <div class="modal-body">
                    @include('inoplate-account::users.form')
                    <div class="form-group">
                      <label for="password" class="control-label">{{ trans('inoplate-account::labels.password') }}</label>
                      <input type="password" name="password" id="password" data-rule-required=true data-rule-minlength={{ config('inoplate.account.password_min_length') }} class="form-control" placeholder="{{ trans('inoplate-account::labels.password') }}">
                      @include('inoplate-adminutes::partials.form-error', ['field' => 'password'])
                    </div>
                    <div class="form-group">
                      <label for="password_confirmation" class="control-label">{{ trans('inoplate-account::labels.password_confirmation') }}</label>
                      <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" data-rule-equalto=[name="password"] placeholder="{{ trans('inoplate-account::labels.password_confirmation') }}">
                      @include('inoplate-adminutes::partials.form-error', ['field' => 'password_confirmation'])
                    </div>
                </div>
                <div class="modal-footer">
                    @section('form-button')
                        <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('inoplate-foundation::labels.cancel') }}</button>
                        <button type="submit" class="btn btn-primary">{{ trans('inoplate-foundation::labels.form.save') }}</button>
                    @show
                </div>
            </form>
            <div class="overlay hide">
                <div class="loading">Loading..</div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" data-backdrop="static" role="dialog" aria-labelledby="form-modal" id="users-update-form">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"> {{ trans('inoplate-account::labels.users.update.title') }} </h4>
            </div>
            <form class="ajax" method="post">
                <input type="hidden" name="_method" value="put" />
                <div class="modal-body">
                    @include('inoplate-account::users.form')
                    <div class="form-group">
                      <label for="password" class="control-label">{{ trans('inoplate-account::labels.password') }}</label>
                      <input type="password" name="password" id="password" data-rule-minlength={{ config('inoplate.account.password_min_length') }} class="form-control" placeholder="{{ trans('inoplate-account::labels.password') }}">
                      @include('inoplate-adminutes::partials.form-error', ['field' => 'password'])
                    </div>
                    <div class="form-group">
                      <label for="password_confirmation" class="control-label">{{ trans('inoplate-account::labels.password_confirmation') }}</label>
                      <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" data-rule-equalto=[name="password"] placeholder="{{ trans('inoplate-account::labels.password_confirmation') }}">
                      @include('inoplate-adminutes::partials.form-error', ['field' => 'password_confirmation'])
                    </div>
                </div>
                <div class="modal-footer">
                    @section('form-button')
                        <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('inoplate-foundation::labels.cancel') }}</button>
                        <button type="submit" class="btn btn-primary">{{ trans('inoplate-foundation::labels.form.save') }}</button>
                    @show
                </div>
            </form>
            <div class="overlay hide">
                <div class="loading">Loading..</div>
            </div>
        </div>
    </div>
</div>