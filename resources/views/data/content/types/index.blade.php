@extends(soda_cms_view_path('layouts.inner'))

@section('content-heading-button')
    @permission('manage-content-types')
        @include(soda_cms_view_path('partials.buttons.create'), ['url' => route('soda.content-types.create')])
    @endpermission
@stop

@section('content')
    <div class="content-top">
        {!! $filter !!}
    </div>

    <div class="content-block">
        {!! $grid !!}
    </div>
@endsection
