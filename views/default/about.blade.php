@extends('soda::default.layout')

@section('content')
	<h1>{{Soda::dynamicModel('soda_about',[])->first()->title}}</h1>

	<div class="text">
		{!! Soda::dynamicModel('soda_about',[])->first()->text !!}
	</div>
	{{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', Soda::dynamicModel('soda_about',[])->first()->date)->diffForHumans() }}

@endsection
