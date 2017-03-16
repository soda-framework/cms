@extends(soda_cms_view_path('layouts.inner'))

@section('head.title')
    <title>Soda CMS | Dashboard</title>
@endsection

@section('breadcrumb')
    <li class="active">Dashboard</li>
@stop

@section('main-content-outer')
    {!! Soda::dashboard()->render() !!}
@stop
