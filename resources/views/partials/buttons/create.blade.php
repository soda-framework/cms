@if(isset($url))
<a class="btn btn-success btn-lg" href="{{ $url }}">
    <span>Create</span>
</a>
@elseif(isset($modal))
<a class="btn btn-success btn-lg" data-toggle="modal" data-target="{{ $modal }}">
    <span>Create</span>
</a>
@else
<button class="btn btn-success btn-lg" {!! isset($submits) ? 'data-submits="' . $submits . '"' : '' !!}>
    <span>Create</span>
</button>
@endif
