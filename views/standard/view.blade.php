@extends(soda_cms_view_path('layouts.inner'))

@section('breadcrumb')
    <ol class="breadcrumb">
        <li><a href="{{ route('soda.home') }}">Home</a></li>
        <li><a href="{{ route('soda.page') }}">Pages</a></li>
        <li><a href="{{ route('soda.page.view', ['id' => $page->id]) }}">{{ $page->name }}</a></li>
        <li><a href="{{ route("soda.page.view", ['id' => $page->id]) }}?tab={{ $block->identifier }}">{{ $block->name }}</a></li>
        <li class="active">{{ $model->id ? 'Edit' : 'New' }}</li>
    </ol>
@stop

@section('head.title')
    <title>Soda CMS | View {{ucfirst($block->name)}}</title>
@endsection

@include(soda_cms_view_path('partials.heading'), [
    'title' => (!$block->id ? 'Create ' : 'Update ') . ucfirst($block->name),
])

@section('content')
    <form method="POST" action='{{route('soda.page.block.edit', ['page_id' => $page->id, 'type' => $block->identifier, 'id' => $model->id])}}' class="form-wrapper">
        <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
        @foreach($block->type->fields as $field)
            {!! SodaForm::field($field)->setModel($model) !!}
        @endforeach
        <button type="submit" class="btn btn-primary">Save</button>
    </form>
@endsection
