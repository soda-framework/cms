@section('content-heading')

<div class="content-header clearfix">
	<div class="pull-left">
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

	<div class="pull-right">
		@yield('content-heading-button')
	</div>
</div>

@stop
