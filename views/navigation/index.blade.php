@extends(soda_cms_view_path('layouts.inner'))

@section('header')

	<title>Navigation</title>

@endsection

@section('content')
	@include(soda_cms_view_path('partials.heading') ,['icon'=>'fa fa-pencil', 'title'=>'Navigation'])
	<p>
		Different menus on pages
	</p>
	{!! $filter !!}
	{!! $grid !!}
	<a class='btn btn-primary' href="{{route('soda.'.$hint.'.create')}}"><span class="fa fa-plus"></span> Create</a>
@endsection
