@extends(soda_cms_view_path('layouts.inner'))

@section('content-heading-button')
    <a class='btn btn-success btn-lg' href="{{route('soda.page-types.create')}}">
        <i class="fa fa-plus"></i>
        <span>Create</span>
    </a>
@stop

@section('content')
    <div class="content-top">
        {!! $filter !!}
    </div>

    <div class="content-block">
        {!! $grid !!}
    </div>
@endsection
