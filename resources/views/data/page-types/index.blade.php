@extends(soda_cms_view_path('layouts.inner'))

@section('breadcrumb')
    <ol class="breadcrumb">
        <li><a href="{{ route('soda.home') }}">Home</a></li>
        <li class="active">Page Types</li>
    </ol>
@stop

@section('head.title')
    <title>Soda CMS | Page Types</title>
@endsection

@section('content-heading-button')
    <a class='btn btn-success btn-lg' href="{{route('soda.page-types.create')}}">
        <i class="fa fa-plus"></i>
        <span>Create</span>
    </a>
@stop

@include(soda_cms_view_path('partials.heading'), [
    'icon'        => 'fa fa-file-o',
    'title'       => 'Page Types',
    'description' => 'Different Page Types have different field types applied to them',
])

@section('content')
    <div class="content-top">
        {!! $filter !!}
    </div>

    <div class="content-block">
        {!! $grid !!}
    </div>
@endsection
