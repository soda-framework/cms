@extends(soda_cms_view_path('layouts.inner'))

@section('breadcrumb')
	<ol class="breadcrumb">
		<li><a href="{{ route('soda.home') }}">Home</a></li>
		<li class="active">Page Types</li>
	</ol>
@stop

@section('header')
	<title>Page Types</title>
@endsection

@section('content')
	@include(soda_cms_view_path('partials.heading'),['icon'=>'fa fa-edit', 'title'=>'Page Types'])
	<p>
		Different Page Types have different field types applied to them
	</p>
	{!! $filter !!}
	{!! $grid !!}
	<a class='btn btn-primary' href="{{route('soda.page_type.create')}}"><span class="fa fa-plus"></span> Create</a>
@endsection
