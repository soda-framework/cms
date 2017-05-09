<?php
$quicklinks = \Soda\Cms\Database\Models\Quicklink::where(function($sq) {
    $sq->whereNull('user_id')->orWhere('user_id', '')->orWhere('user_id', Auth::guard('soda')->user()->id);
})->get();
?>

<div class="col-xs-12">
    <h2>Quick Links</h2>
    @foreach($quicklinks as $quicklink)
        <a href="{{ $quicklink->getUrl() }}" class="tag">
          <span>{{ $quicklink->text }}</span>
          @if($quicklink->user_id == Auth::guard('soda')->user()->id)
          <span class="tag-delete">&times;</span>
          @endif
        </a>
    @endforeach
</div>
