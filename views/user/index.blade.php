@extends(config('soda.hint_path').'::layouts.inner')

@section('header')

	<title>Users</title>
@endsection

@section('content')
	@include(config('soda.hint_path').'::partials.heading',['icon'=>'fa fa-users', 'title'=>'Users'])
	<p>
		Fields are added onto pages.
	</p>
	{!! $filter !!}
	{!! $grid !!}
	<a class='btn btn-primary' href="{{route('soda.'.$hint.'.create')}}"><span class="fa fa-users"></span> Create</a>
@endsection