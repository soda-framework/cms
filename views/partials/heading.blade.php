@section('content-heading')

<div class="content-header clearfix">
	<h1>
		@if(isset($icon))
		<i class="{{ $icon }}"></i>
		@endif
		<span>{{ $title }}</span>
	</h1>
	@if(isset($description))
	<p class="text-muted">{{ $description }}</p>
	@endif
</div>

@stop
