<div class="form-group has-warning">
  <label for="email" class="control-label">{{ trans('inoplate-account::labels.email') }} {{ trans('inoplate-account::labels.email_change_warning') }}</label>
  <input type="text" name="email" id="email" class="form-control" data-rule-required=true data-rule-email=true value="{{ old('email', $user->email) }}" placeholder="{{ trans('inoplate-account::labels.email') }}">
  @include('inoplate-adminutes::partials.form-error', ['field' => 'email'])
</div>
<div class="form-group">
  <label for="username" class="control-label">{{ trans('inoplate-account::labels.username') }}</label>
  <input type="text" name="username" id="username" class="form-control" data-rule-required=true value="{{ old('username', $user->username) }}" placeholder="{{ trans('inoplate-account::labels.username') }}"/>
  @include('inoplate-adminutes::partials.form-error', ['field' => 'username'])
</div>
<div class="form-group">
  <label for="name" class="control-label">{{ trans('inoplate-account::labels.name') }}</label>
  <input type="text" name="name" id="name" class="form-control" data-rule-required=true placeholder="{{ trans('inoplate-account::labels.name') }}" value="{{ old('name', $user->name) }}">
  @include('inoplate-adminutes::partials.form-error', ['field' => 'name'])
</div>
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