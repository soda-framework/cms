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

@section('content-heading-button')
    @include(soda_cms_view_path('partials.buttons.save'), ['submits' => '#dynamic-block-form'])
@stop

@include(soda_cms_view_path('partials.heading'), [
    'title' => (!$block->id ? 'Create ' : 'Update ') . ucfirst($block->name),
])

@section('content')
    <div class="content-block">
        <form method="POST" id="dynamic-block-form" action='{{route('soda.page.block.edit', ['page_id' => $page->id, 'type' => $block->identifier, 'id' => $model->id])}}' class="form-wrapper" enctype="multipart/form-data">
            <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
            @foreach($block->type->fields as $field)
                {!! SodaForm::field($field)->setModel($model) !!}
            @endforeach
        </form>
    </div>

    <div class="content-bottom">
        @include(soda_cms_view_path('partials.buttons.save'), ['submits' => '#dynamic-block-form'])
    </div>
@endsection
