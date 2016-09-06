@extends(soda_cms_view_path('layouts.inner'))

@section('breadcrumb')
	<ol class="breadcrumb">
		<li><a href="{{ route('soda.home') }}">Home</a></li>
		<li class="active">Page Types</li>
	</ol>
@stop

@section('head.title')
	<title>Soda CMS | Page Types</title>
@endsection

@include(soda_cms_view_path('partials.heading'), [
    'icon'        => 'fa fa-edit',
    'title'       => 'Page Types',
    'description' => 'Different Page Types have different field types applied to them',
])

@section('content')
	{!! $filter !!}
	{!! $grid !!}
	<a class='btn btn-primary' href="{{route('soda.page_type.create')}}"><span class="fa fa-plus"></span> Create</a>
@endsection
