@extends('soda-example::layouts.master')

@section('content')
	You are logged in {{ Auth::guard('soda-example')->user()->email }}
@endsection
