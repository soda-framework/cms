@extends(soda_cms_view_path('layouts.inner'), ['body_class' => 'dashboard'])

@section('main-content-outer')
    <h1 class="text__theme-secondary">@lang('soda::phrases.welcome', ['name' => '<span class="text__theme-primary">' . Auth::guard('soda')->user()->username . '</span>'])</h1>
    {!! app('soda.dashboard')->render() !!}
@stop
