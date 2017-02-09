@extends(soda_cms_view_path('layouts.inner'))

@section('breadcrumb')
    <ol class="breadcrumb">
        <li><a href="{{ route('soda.home') }}">Home</a></li>
        <li><a href="{{ route('soda.pages.index') }}">Pages</a></li>
        <li><a href="{{ route('soda.pages.edit', $page->id) }}">{{ $page->name }}</a></li>
        <li><a href="{{ route("soda.pages.edit", $page->id) }}?tab={{ strtolower($blockType->identifier) }}">{{ $blockType->name }}</a></li>
        <li class="active">{{ $block->id ? 'Edit' : 'New' }}</li>
    </ol>
@stop

@section('head.title')
    <title>{{ $block->id ? 'Edit' : 'New' }} {{ ucfirst($blockType->name) }} :: Soda CMS</title>
@endsection

@section('content-heading-button')
    @include(soda_cms_view_path('partials.buttons.save'), ['submits' => '#dynamic-block-form'])
@stop

@include(soda_cms_view_path('partials.heading'), [
    'title' => (!$block->id ? 'Create ' : 'Update ') . ucfirst($blockType->name),
])

@section('content')
    <div class="content-block">
        <form method="POST" id="dynamic-block-form" action='{{ route('soda.pages.block-types.block.' . ($block->id ? 'update' : 'store'), [$page->id, $blockType->id, $block->id]) }}' class="form-wrapper" enctype="multipart/form-data">
            {!! csrf_field() !!}
            {!! method_field($block->id ? 'PUT' : 'POST') !!}
            @foreach($blockType->fields as $field)
                {!! SodaForm::field($field)->setModel($block) !!}
            @endforeach
        </form>
    </div>

    <div class="content-bottom">
        @include(soda_cms_view_path('partials.buttons.save'), ['submits' => '#dynamic-block-form'])
    </div>
@endsection
