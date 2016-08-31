@extends(soda_cms_view_path('layouts.inner'))

@section('header')

	<title>Page Types</title>

@endsection

@section('content')
	@include(soda_cms_view_path('partials.heading'),['icon'=>'fa fa-pencil-square', 'title'=>'Block Types'])
	<p>
		Different Block Types have different field types applied to them
	</p>
	{!! $filter !!}
	{!! $grid !!}
	<a class='btn btn-primary' href="{{route('soda.'.$hint.'.create')}}"><span class="fa fa-plus"></span> Create</a>
@endsection
