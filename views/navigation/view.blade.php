@extends(config('soda.hint_path').'::layouts.inner')

@section('header')

	<title>Navigation</title>

@endsection

@section('content')
	@if(!$model->parent_id)
		@include('soda::navigation.root')
	@else
		@include('soda::navigation.item')
	@endif


@endsection