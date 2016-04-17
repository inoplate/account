<div class="box box-widget widget-user">
  <div class="widget-user-header {{ $cardCover or 'bg-aqua-active' }}">
    <h3 class="widget-user-username">{{ $user['name'] or '' }}</h3>
    <h5 class="widget-user-desc">{{ $user['type'] or trans('inoplate-foundation::labels.users.type.generic') }}</h5>
  </div>
  <div class="widget-user-image media-selector">
    <img class="img-circle" src="{{ $user['avatar'] or '/vendor/inoplate-adminutes/img/user-128x128.png' }}" alt="User Avatar">
    <div class="overlay img-circle">
      <i class="fa fa-image"></i>
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

@push('header-styles-stack')
    <link href="/vendor/inoplate-account/widgets/user-card/user-card.css" type="text/css" rel="stylesheet" />
@endpush