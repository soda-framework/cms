@extends(config('soda.hint_path').'::layouts.inner')

@section('header')

    <title>Pages</title>
    {{--note: non of these have anything in them anymore--}}
    <!-- JavaScripts -->
    <script src="{{ elixir('soda/soda/js/content.js') }}"></script>
    <!-- Styles -->
    <link href="{{ elixir('soda/soda/css/content.css') }}" rel="stylesheet">

@endsection

@section('content')
    <h1>Pages</h1>
    {!! $tree !!}
    <a href="{{route($routeHint.'create')}}">Create Page.</a>
@endsection
