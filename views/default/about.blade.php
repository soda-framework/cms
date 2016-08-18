@extends('soda::default.layout')

@section('content')
	<h1>{{ $page->model()->title }}</h1>

	<div class="text">
		{!! $page->model()->text !!}
	</div>
	{{ $page->model()->date ? \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $page->model()->date)->diffForHumans() : '' }}

@endsection
