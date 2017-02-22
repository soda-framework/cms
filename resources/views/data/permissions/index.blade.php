@extends(soda_cms_view_path('layouts.inner'))

@section('breadcrumb')
    <li><a href="{{ route('soda.home') }}">Home</a></li>
    <li class="active">Permissions</li>
@stop

@section('head.cms')
    <title>Permissions :: Soda CMS</title>
@endsection

@section('content-heading-button')
    @include(soda_cms_view_path('partials.buttons.create'), ['url' => route('soda.permissions.create')])
@stop

@include(soda_cms_view_path('partials.heading'), [
    'icon'        => 'fa fa-unlock',
    'title'       => 'Permissions',
])

@section('content')
    <div class="content-top">
        {!! $filter !!}
    </div>

    <div class="content-block">
        {!! $grid !!}
    </div>
@endsection
