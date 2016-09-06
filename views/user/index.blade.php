@extends(soda_cms_view_path('layouts.inner'))

@section('breadcrumb')
	<ol class="breadcrumb">
		<li><a href="{{ route('soda.home') }}">Home</a></li>
		<li class="active">Users</li>
	</ol>
@stop

@section('head.cms')
	<title>Soda CMS | Users</title>
@endsection

@include(soda_cms_view_path('partials.heading'), [
    'icon'        => 'fa fa-users',
    'title'       => 'Users',
])

@section('content')
	{!! $filter !!}
	{!! $grid !!}
	<a class='btn btn-primary' href="{{route('soda.'.$hint.'.create')}}"><span class="fa fa-users"></span> Create</a>
@endsection
