@extends(soda_cms_view_path('layouts.inner'))

@section('content-heading-button')
    @include(soda_cms_view_path('partials.buttons.create'), ['url' => route('soda.fields.create')])
@stop

@include(soda_cms_view_path('partials.heading'), [
    'icon'        => 'mdi mdi-paperclip',
    'title'       => 'Fields',
    'description' => 'Fields are added onto pages.',
])

@section('content')
    <div class="content-top">
        {!! $filter !!}
    </div>

    <div class="content-block">
        {!! $grid !!}
    </div>
@endsection
