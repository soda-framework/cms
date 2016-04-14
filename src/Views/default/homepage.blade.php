@extends('soda::default.layout')

@section('content')
	This might be the 1st design.
	@include('soda::blocks.banners',['block'=>Soda::getBlock('banners')])

	@include('soda::blocks.polling',['block'=>Soda::getBlock('polling')])
@stop