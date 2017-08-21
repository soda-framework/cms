<div class="col-xs-12">
    <h2>@lang('soda::misc.quicklinks')</h2>
    @foreach(app('soda.interface')->quicklinks() as $quicklink)
        <a href="{{ $quicklink->getUrl() }}" class="tag">
          <span>{{ $quicklink->text }}</span>
          @if($quicklink->user_id == Auth::guard('soda')->user()->id)
          <span class="tag-delete">&times;</span>
          @endif
        </a>
    @endforeach
</div>
