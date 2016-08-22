@extends(config('soda.hint_path').'::layouts.app')

@section('main-content')
	<div class="row">
		@include(config('soda.hint_path'). "::partials.sidebar")
		<div class="col-sm-offset-2 col-sm-10 main-content">
			<div class="main-content-inner">
				@yield('content')
			</div>
		</div>
	</div>
@endsection
