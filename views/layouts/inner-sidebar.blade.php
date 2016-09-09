@extends(soda_cms_view_path('layouts.app'))

@section('main-content')
	<div class="row">
		@include(soda_cms_view_path('partials.sidebar'))
		<div class="col-sm-offset-2 col-sm-10 main-content">
			@yield('breadcrumb')
			<div class="container-fluid">
				<div class="row">
					<div class="col-xs-12">
						@yield('content-heading')
					</div>
				</div>
				<div class="row">
					<div class="col-md-3 col-xs-12 pull-right">
						<div class="main-content-sidebar">
							@yield('content.sidebar')
						</div>
					</div>
					<div class="col-md-9 col-xs-12">
						<div class="main-content-inner has-sidebar">
							@yield('content')
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection
