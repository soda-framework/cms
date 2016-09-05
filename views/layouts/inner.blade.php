@extends(soda_cms_view_path('layouts.app'))

@section('main-content')
	<div class="row">
		@include(soda_cms_view_path('partials.sidebar'))
		<div class="col-sm-offset-2 col-sm-10 main-content">
			@yield('breadcrumb')
			@yield('content-heading')
			<div class="main-content-inner">
				@yield('content')
			</div>
		</div>
	</div>
@endsection
