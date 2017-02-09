@extends(soda_cms_view_path('layouts.inner'))

@section('breadcrumb')
    <ol class="breadcrumb">
        <li><a href="{{ route('soda.home') }}">Home</a></li>
        <li class="active">Fields</li>
    </ol>
@stop

@section('head.title')
    <title>Fields :: Soda CMS</title>
@endsection

@section('content-heading-button')
    @include(soda_cms_view_path('partials.buttons.create'), ['url' => route('soda.fields.create')])
@stop

@include(soda_cms_view_path('partials.heading'), [
    'icon'        => 'fa fa-paperclip',
    'title'       => 'Fields',
    'description' => 'Fields are added onto pages.',
])

@section('content')
    <div class="content-block">
        {!! $filter !!}
        {!! $grid !!}
    </div>
@endsection
