@if(isset($url))
<a class="btn btn-success btn-lg" href="{{ $url }}">
    <i class="fa fa-plus"></i>
    <span>Create</span>
</a>
@elseif(isset($modal))
<a class="btn btn-success btn-lg" data-toggle="modal" data-target="{{ $modal }}">
    <i class="fa fa-plus"></i>
    <span>Create</span>
</a>
@else
<button class="btn btn-success btn-lg" {!! isset($submits) ? 'data-submits="#page-type-form"' : '' !!}>
    <i class="fa fa-plus"></i>
    <span>Create</span>
</button>
@endif
