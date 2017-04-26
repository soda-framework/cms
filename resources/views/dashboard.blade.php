@extends(soda_cms_view_path('layouts.inner'))

@section('main-content-outer')
    {!! app('soda.dashboard')->render() !!}
@stop
