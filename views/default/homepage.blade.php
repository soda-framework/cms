@extends('soda::default.layout')

@section('content')
	<h1>{{Soda::dynamicModel('soda_homepage',[])->first()->title}}</h1>

	<img width=100 src="{{Soda::dynamicModel('soda_homepage',[])->where('page_id',$page->id)->first()->image}}" />
	<div class="text">
		{!! Soda::dynamicModel('soda_homepage',[])->first()->text !!}
	</div>
	{!! Soda::getMenuBuilder()->menu('Main Menu') !!}

	{!! Soda::getMenuBuilder()->menu('Social') !!}
@stop
