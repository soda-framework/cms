@extends('soda_theme_hint::layouts.master')

@section('content')
	You are logged in {{Auth::guard('username')->user()->email}}
@endsection
