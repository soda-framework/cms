@extends('soda-example::layouts.master')

@section('content')
	You are logged in {{Auth::guard('username')->user()->email}}
@endsection
