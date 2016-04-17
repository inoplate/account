<div class="form-group">
  <input type="hidden" name="_token" value="{{ csrf_token() }}" />
  <label for="name" class="control-label">{{ trans('inoplate-foundation::labels.name') }}</label>
  <input type="text" name="name" id="name" class="form-control" data-rule-required=true value="{{ old('name', isset($role['name']) ? $role['name'] : '' ) }}" placeholder="{{ trans('inoplate-foundation::labels.name') }}">
  @include('inoplate-adminutes::partials.form-error', ['field' => 'name'])
</div>
<div class="form-group">
  <label for="slug" class="control-label">{{ trans('inoplate-foundation::labels.slug') }}</label>
  <input type="text" name="slug" id="slug" class="form-control" data-rule-required=true value="{{ old('slug', isset($role['description']['slug']) ? $role['description']['slug'] : '' ) }}" placeholder="{{ trans('inoplate-foundation::labels.slug') }}">
  @include('inoplate-adminutes::partials.form-error', ['field' => 'slug'])
</div>