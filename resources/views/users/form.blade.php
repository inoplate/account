@php($userRoles = [])

@if(isset($user['roles']))
  @php($userRoles = array_pluck($user['roles'], 'id'))
@endif

@php($status = !old('status') ? !isset($user['description']['active']) ? 0 : $user['description']['active'] : old('status'))

<div class="form-group">
  <input type="hidden" name="_token" value="{{ csrf_token() }}" />
  <label for="email" class="control-label">{{ trans('inoplate-account::labels.email') }}</label>
  <input type="text" name="email" id="email" class="form-control" data-rule-required=true data-rule-email=true value="{{ old('email', isset($user['email']) ? $user['email'] : '' ) }}" placeholder="{{ trans('inoplate-account::labels.email') }}">
  @include('inoplate-adminutes::partials.form-error', ['field' => 'email'])
</div>
<div class="form-group">
  <label for="username" class="control-label">{{ trans('inoplate-account::labels.username') }}</label>
  <input type="text" name="username" id="username" class="form-control" data-rule-required=true value="{{ old('username', isset($user['username']) ? $user['username'] : '' ) }}" placeholder="{{ trans('inoplate-account::labels.username') }}"/>
  @include('inoplate-adminutes::partials.form-error', ['field' => 'username'])
</div>
<div class="form-group">
  <label for="roles" class="control-label">{{ trans('inoplate-account::labels.roles.title') }}</label>
  <select id="roles" class="form-control" name="roles[]" multiple="multiple" data-rule-required=true style="width:100%"  data-placeholder="{{ trans('inoplate-account::labels.users.form.roles') }}">
    @foreach($roles as $role)
      <option {{ in_array($role->id()->value(), $userRoles) ? 'selected' : '' }} value="{{ $role->id()->value() }}">{{ $role->name()->value() }}</option>
    @endforeach
  </select>
  @include('inoplate-adminutes::partials.form-error', ['field' => 'roles'])
</div>
<div class="form-group">
  <label for="status" class="control-label">{{ trans('inoplate-account::labels.users.status') }}</label>
  <select id="status" class="form-control" name="status" data-rule-required=true style="width:100%"  data-placeholder="{{ trans('inoplate-account::labels.users.status') }}">
    <option {{ $status == 1 ? "selected" : "" }} value="1">{{ trans('inoplate-account::labels.users.form.status.active') }}</option>
    <option {{ $status == 0 ? "selected" : "" }} value="0">{{ trans('inoplate-account::labels.users.form.status.inactive') }}</option>
  </select>
  @include('inoplate-adminutes::partials.form-error', ['field' => 'status'])
</div>
<div class="form-group">
  <label for="name" class="control-label">{{ trans('inoplate-account::labels.name') }}</label>
  <input type="text" name="name" id="name" class="form-control" data-rule-required=true placeholder="{{ trans('inoplate-account::labels.name') }}" value="{{ old('name', isset($user['description']['name']) ? $user['description']['name'] : '') }}">
  @include('inoplate-adminutes::partials.form-error', ['field' => 'name'])
</div>