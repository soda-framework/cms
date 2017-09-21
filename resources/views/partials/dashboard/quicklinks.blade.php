<?php
$quicklinks = app('soda.interface')->quicklinks();
?>

@if(count($quicklinks))
    <div class="col-xs-12">
        <h2>@langtest('soda::terminology.quicklink_plural')</h2>
        @foreach($quicklinks as $quicklink)
            <a href="{{ $quicklink->getUrl() }}" class="tag">
                <span>{{ $quicklink->text }}</span>
                @if($quicklink->user_id == Auth::guard('soda')->user()->id)
                    <span class="tag-delete">&times;</span>
                @endif
            </a>
        @endforeach
    </div>
@endif
