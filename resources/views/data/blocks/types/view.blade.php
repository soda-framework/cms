@extends(soda_cms_view_path('layouts.inner'))

@section('breadcrumb')
    <ol class="breadcrumb">
        <li><a href="{{ route('soda.home') }}">Home</a></li>
        <li><a href="{{ route('soda.blocks.index') }}">Blocks</a></li>
        <li class="active">{{ $block->name ? $block->name : 'New '. ($block->type ? $block->type->name . " Block" : "Block") }}</li>
    </ol>
@stop

@section('head.title')
    <title>{{ $block->id ? 'Edit' : 'New' }} Block :: Soda CMS</title>
@endsection

@section('content-heading-button')
    @include(soda_cms_view_path('partials.buttons.save'), ['submits' => '#block-form'])
@stop

@include(soda_cms_view_path('partials.heading'), [
    'icon'        => 'fa fa-pencil',
    'title'       => $block->name ? 'Block: ' . $block->name : 'New Block',
])

@section('content')
    <div class="content-block">

        <form method="POST" id="block-form" action="{{ route('soda.blocks.' . ($block->id ? 'update' : 'store'), $block->id) }}" enctype="multipart/form-data">
            {!! csrf_field() !!}
            {!! method_field($block->id ? 'PUT' : 'POST') !!}
            <input type="hidden" name="block_type_id" value="{{ $block->block_type_id }}"/>

            {!! SodaForm::text([
                'name'        => 'Block Name',
                'field_name'  => 'name',
            ])->setModel($block) !!}

            {!! SodaForm::toggle([
                'name'         => 'Shared',
                'field_name'   => 'is_shared',
                'description'  => 'Whether or not the contents of this block should be shared across all pages. Changing this field affects current block contents.',
            ])->setModel($block) !!}

            {!! SodaForm::textarea([
                'name'        => 'Block Description',
                'field_name'  => 'description',
            ])->setModel($block) !!}

            <div class="row fieldset-group">
                <div class="col-sm-6 col-xs-12">
                    {!! SodaForm::dropdown([
                        'name'        => 'List Action',
                        'field_name'  => 'list_action_type',
                        'field_params' => ['options' => Soda\Cms\Foundation\Constants::PAGE_ACTION_TYPES],
                        'description'  => 'Specifies the interface supplied when listing this block.',
                    ])->setModel($block)->setLayout(soda_cms_view_path('partials.inputs.layouts.inline-group')) !!}
                </div>
                <div class="col-sm-6 col-xs-12">
                    {!! SodaForm::text([
                        'name'        => null,
                        'field_name'  => 'list_action',
                    ])->setModel($block)->setLayout(soda_cms_view_path('partials.inputs.layouts.inline-group')) !!}
                </div>
            </div>

            <div class="row fieldset-group">
                <div class="col-sm-6 col-xs-12">
                    {!! SodaForm::dropdown([
                        'name'        => 'Edit Action',
                        'field_name'  => 'edit_action_type',
                        'field_params' => ['options' => Soda\Cms\Foundation\Constants::PAGE_ACTION_TYPES],
                        'description'  => 'Specifies the interface supplied when editing this block.',

                    ])->setModel($block)->setLayout(soda_cms_view_path('partials.inputs.layouts.inline-group')) !!}
                </div>
                <div class="col-sm-6 col-xs-12">
                    {!! SodaForm::text([
                        'name'        => null,
                        'field_name'  => 'edit_action',
                    ])->setModel($block)->setLayout(soda_cms_view_path('partials.inputs.layouts.inline-group')) !!}
                </div>
            </div>

            {!! SodaForm::text([
                'name'        => 'Block Identifier',
                'field_name'  => 'identifier',
            ])->setModel($block) !!}
        </form>
    </div>

    <div class="content-bottom">
        @include(soda_cms_view_path('partials.buttons.save'), ['submits' => '#block-form'])
    </div>
@endsection
