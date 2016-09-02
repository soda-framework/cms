@extends(soda_cms_view_path('layouts.inner'))

@section('breadcrumb')
	<ol class="breadcrumb">
		<li><a href="{{ route('soda.home') }}">Home</a></li>
		<li class="active">Fields</li>
	</ol>
@stop

@section('header')
	<title>Fields</title>
@endsection

@section('content')
	@include(soda_cms_view_path('partials.heading'), ['icon'=>'fa fa-pencil', 'title'=>'Fields'])
	<p>
		Fields are added onto pages.
	</p>
	{!! $filter !!}
	{!! $grid !!}
	<a class='btn btn-primary' href="{{route('soda.'.$hint.'.create')}}"><span class="fa fa-plus"></span> Create</a>
@endsection
