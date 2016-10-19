@extends(soda_cms_view_path('layouts.inner'))

@section('breadcrumb')
    <ol class="breadcrumb">
        <li><a href="{{ route('soda.home') }}">Home</a></li>
        <li class="active">Users</li>
    </ol>
@stop

@section('head.cms')
    <title>Soda CMS | Users</title>
@endsection

@section('content-heading-button')
    @include(soda_cms_view_path('partials.buttons.create'), ['url' => route('soda.'.$hint.'.create')])
@stop

@include(soda_cms_view_path('partials.heading'), [
    'icon'        => 'fa fa-users',
    'title'       => 'Users',
])

@section('content')
    <div class="content-top">
        {!! $filter !!}
    </div>

    <div class="content-block">
        {!! $grid !!}
    </div>
@endsection
