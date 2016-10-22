@extends(soda_cms_view_path('layouts.inner'))

@section('breadcrumb')
    <ol class="breadcrumb">
        <li><a href="{{ route('soda.home') }}">Home</a></li>
        <li class="active">Roles</li>
    </ol>
@stop

@section('head.cms')
    <title>Soda CMS | Roles</title>
@endsection

@section('content-heading-button')
    @include(soda_cms_view_path('partials.buttons.create'), ['url' => route('soda.roles.create')])
@stop

@include(soda_cms_view_path('partials.heading'), [
    'icon'        => 'fa fa-flag',
    'title'       => 'Roles',
])

@section('content')
    <div class="content-top">
        {!! $filter !!}
    </div>

    <div class="content-block">
        {!! $grid !!}
    </div>
@endsection
