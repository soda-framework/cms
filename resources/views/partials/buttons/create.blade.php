@if(isset($url))
<a class="btn btn-success btn-lg" href="{{ $url }}">
    <span>@lang('soda::actions.create')</span>
</a>
@elseif(isset($modal))
<a class="btn btn-success btn-lg" data-toggle="modal" data-target="{{ $modal }}">
    <span>@lang('soda::actions.create')</span>
</a>
@else
<button class="btn btn-success btn-lg" {!! isset($submits) ? 'data-submits="' . $submits . '"' : '' !!}>
    <span>@lang('soda::actions.create')</span>
</button>
@endif
