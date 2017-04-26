@extends(soda_cms_view_path('layouts.inner'))

@section('content-heading-button')
    @permission('manage-page-types')
        @include(soda_cms_view_path('partials.buttons.create'), ['url' => route('soda.page-types.create')])
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
