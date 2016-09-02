@extends(soda_cms_view_path('layouts.inner'))

@section('breadcrumb')
    <ol class="breadcrumb">
        <li><a href="{{ route('soda.home') }}">Home</a></li>
        <li><a href="{{ route('soda.page') }}">Pages</a></li>
        <li><a href="{{ route('soda.page.view', ['id' => $page->id]) }}">{{ $page->name }}</a></li>
        <li class="active">{{ $block->name }}</li>
    </ol>
@stop

@section('header')
    <title>View {{ucfirst($block->name)}}</title>
@endsection

@section('content')
    <h1>
        @if(!$block->id)
            Create {{ucfirst($block->name)}}
        @else
            Update {{ucfirst($block->name)}}
        @endif
        @if($page->id)
            <a href="{{ route("soda.page.view", ['id' => $page->id]) }}?tab={{ $block->identifier }}" class="btn btn-warning pull-right">Back to page</a>
        @endif
    </h1>
    <br />

    <form method="POST" action='{{route('soda.page.block.edit', ['page_id' => $page->id, 'type' => $block->identifier, 'id' => $model->id])}}' class="form-wrapper">
        <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
        @foreach($block->type->fields as $field)
            {!! SodaForm::field($field)->setModel($model) !!}
        @endforeach
        <button type="submit" class="btn btn-primary">Save</button>
    </form>
@endsection
