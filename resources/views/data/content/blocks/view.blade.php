@extends(soda_cms_view_path('layouts.inner'))

@section('content-heading-button')
    @include(soda_cms_view_path('partials.buttons.save'), ['submits' => '#dynamic-block-form'])
@stop

@section('content')
    <div class="content-block">
        <form method="POST" id="dynamic-block-form" action='{{ route('soda.pages.block-types.block.' . ($block->id ? 'update' : 'store'), [$page->id, $blockType->id, $block->id]) }}' class="form-wrapper" enctype="multipart/form-data">
            {!! csrf_field() !!}
            {!! method_field($block->id ? 'PUT' : 'POST') !!}
            @foreach($blockType->fields as $field)
                {!! app('soda.form')->field($field)->setModel($block) !!}
            @endforeach
        </form>
    </div>

    <div class="content-bottom">
        @include(soda_cms_view_path('partials.buttons.save'), ['submits' => '#dynamic-block-form'])
    </div>
@endsection
