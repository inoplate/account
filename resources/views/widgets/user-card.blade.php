@php($userId = isset($user['id']) ? $user['id'] : '')

<div class="box box-widget widget-user">
  <div class="widget-user-header {{ $cardCover or 'bg-aqua-active' }}">
    <h3 class="widget-user-username">{{ $user['name'] or '' }}</h3>
    <h5 class="widget-user-desc">{{ $user['description']['type'] or trans('inoplate-foundation::labels.users.type.generic') }}</h5>
  </div>

  
  <div class="media-selector-wrapper" data-non-image-error="{{ trans('inoplate-account::messages.profile.avatar_must_be_image') }}">
    @include('inoplate-media::library.finder')
    <div class="widget-user-image media-selector">
      <img class="img-circle" src="{{ $user['avatar'] or '/vendor/inoplate-adminutes/img/user-128x128.png' }}" alt="User Avatar">
      <div class="overlay img-circle">
        <i class="fa fa-image"></i>
      </div>
      @if(isset($user))
        <form class="hide ajax" action="{{ url('/admin/profile/avatar', [$userId]) }}" method="post">
          <input type="hidden" name="_token" value="{{ csrf_token() }}" />
          <input type="hidden" name="_method" value="put" />
          <input type="text" name="avatar" value="{{ $user['avatar'] or '/vendor/inoplate-adminutes/img/user-128x128.png' }}"/>
        </form>
      @endif
    </div>
  </div>
    <div class="box-footer">
      <div class="row">
        <div class="col-sm-12">
          <div class="description-block">
            <h5 class="description-header">&nbsp;</h5>
            <span class="description-text">&nbsp;</span>
          </div>
        </div>
      </div>
    </div>
</div>

@addCss([
  'vendor/inoplate-account/widgets/user-card/user-card.css'
])

@addJs([
  'vendor/inoplate-account/widgets/user-card/user-card.js'
])