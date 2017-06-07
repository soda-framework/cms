@extends(soda_cms_view_path('layouts.inner'), ['body_class' => 'dashboard'])

@section('main-content-outer')
    <h1><strong class="text__theme-secondary">Welcome,</strong> <strong class="text__theme-primary">{{ Auth::guard('soda')->user()->username }}</strong></h1>
    {!! app('soda.dashboard')->render() !!}
@stop
