@extends(soda_cms_view_path('layouts.inner'))

@section('breadcrumb')
    <li><a href="{{ route('soda.home') }}">Home</a></li>
    <li class="active">Block Types</li>
@stop

@section('head.title')
	<title>Block Types :: Soda CMS</title>
@endsection

@section('content-heading-button')
	@include(soda_cms_view_path('partials.buttons.create'), ['url' => route('soda.block-types.create')])
@stop

@include(soda_cms_view_path('partials.heading'), [
    'icon'        => 'mdi mdi-widgets',
    'title'       => 'Block Types',
    'description' => 'Different Block Types have different field types applied to them',
])

@section('content')
	<div class="content-top">
		{!! $filter !!}
	</div>

	<div class="content-block">
		{!! $grid !!}
	</div>
@endsection
