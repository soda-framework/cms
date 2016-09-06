@extends(soda_cms_view_path('layouts.inner'))

@section('head.title')
    <title>Soda CMS | Dashboard</title>
@endsection

@section('content')
    <div class="jumbotron home-intro">
        <h1 class="display-3">Welcome to Soda CMS!</h1>
        <hr class="m-y-2">
        <p class="lead">This is your dashboard.</p>
        {{--
        <p class="lead">
            <a class="btn btn-primary btn-lg" href="#" role="button">Learn more</a>
        </p>
        --}}
    </div>
@stop
