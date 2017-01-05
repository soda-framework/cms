<?php
$smallView = false;
if (!$model->type || !count($model->type->fields)) {
    $smallView = true;
}

$blocks = $model->blocks->keyBy('block_id');
if($model->type && $model->type->blocks) {
    $blocks->merge($model->type->blocks->keyBy('block_id'));
}
?>

@extends(soda_cms_view_path('layouts.inner'))

@section('breadcrumb')
    <ol class="breadcrumb">
        <li><a href="{{ route('soda.home') }}">Home</a></li>
        <li><a href="{{ route('soda.page') }}">Pages</a></li>
        <li class="active">{{ $model->name ? $model->name : 'New ' . ($model->type ? $model->type->name . " Page" : "Page") }}</li>
    </ol>
@stop

@section('head.title')
    <title>Soda CMS | View Page</title>
@endsection

@section('content-heading-button')
    @include(soda_cms_view_path('partials.buttons.save'), ['submits' => '#page-form'])
@stop

@include(soda_cms_view_path('partials.heading'), [
    'icon'        => 'fa fa-file-o',
    'title'       => $model->name? ' Page: ' . $model->name : 'New Page',
    'description' => $model->description,
])

@section('tab.settings')
    <div class="row">
        @if(!$smallView)
        <div class="col-md-9 col-xs-12">
            <div class="content-block">
                @if($model->type->description)
                    <p>{{ $model->type->description }}</p>
                    <hr/>
                @endif
                @if($model->type && $model->type->fields)
                    @foreach($model->type->fields as $field)
                        {!! SodaForm::field($field)->setModel(@$page_table)->setPrefix('settings') !!}
                    @endforeach
                @endif
            </div>
        </div>
        @endif
        <div class="{{ $smallView ? 'col-md-12' : 'col-md-3 pull-right' }} col-xs-12">
            <div class="content-block">
                {!! SodaForm::text([
                    "name"        => "Name",
                    "description" => "The name of this page",
                    "field_name"  => 'name',
                ])->setLayout($smallView ? soda_cms_view_path('partials.inputs.layouts.inline') : soda_cms_view_path('partials.inputs.layouts.stacked'))->setModel($model) !!}

                {!! SodaForm::slug([
                    'name'        => 'Slug',
                    'description' => 'The url of this page',
                    'field_name'  => 'slug',
                    'field_params' => [
                        'prefix' => ($model->parent_id !== null && $parent = $model->getParent()) ? $parent->slug : '',
                    ],
                ])->setLayout($smallView ? soda_cms_view_path('partials.inputs.layouts.inline') : soda_cms_view_path('partials.inputs.layouts.stacked'))->setModel($model) !!}

                {!! SodaForm::toggle([
                    'name'         => 'Published',
                    'field_name'   => 'status',
                    'value'        => Soda\Cms\Support\Constants::STATUS_LIVE,
                    'field_params' => ['checked-value' => Soda\Cms\Support\Constants::STATUS_LIVE, 'unchecked-value' => Soda\Cms\Support\Constants::STATUS_DRAFT],
                ])->setLayout($smallView ? soda_cms_view_path('partials.inputs.layouts.inline') : soda_cms_view_path('partials.inputs.layouts.stacked'))->setModel($model) !!}

            </div>
        </div>
    </div>
@stop

@section('tab.live-preview')
    <div class="content-block">
        <p>Use this tab to customise information on the page in a live view</p>
        <hr/>
        @if($model->slug)
            <p>{{ $model->slug }}</p>
            <iframe width="100%" height=400 src="{{ $model->slug }}?soda_edit=true"></iframe>
        @else
            <p>You must set a slug to enabled this feature.</p>
        @endif
    </div>
@stop

@section('tab.advanced')
    <div class="content-block">
        <p>Advanced page settings</p>
        <hr/>

        {!! SodaForm::text([
            'name'        => 'Package Prefix',
            'field_name'  => 'package',
            'value'       => $model->type && $model->type->package ? $model->type->package : '',
        ])->setModel($model) !!}

        <div class="row fieldset-group">
            <div class="col-sm-6 col-xs-12">
                {!! SodaForm::dropdown([
                    'name'         => 'Action',
                    'field_name'   => 'action_type',
                    'value'        => $model->type && $model->type->action_type ? $model->type->action_type : 'view',
                    'field_params' => ['options' => Soda::getPageBuilder()->getActionTypes()],
                ])->setModel($model)->setLayout(soda_cms_view_path('partials.inputs.layouts.inline-group')) !!}
            </div>
            <div class="col-sm-6 col-xs-12">
                {!! SodaForm::text([
                    'name'        => null,
                    'field_name'  => 'action',
                    'value'       => $model->type && $model->type->action ? $model->type->action : '',
                ])->setModel($model)->setLayout(soda_cms_view_path('partials.inputs.layouts.inline-group')) !!}
            </div>
        </div>

        <div class="row fieldset-group">
            <div class="col-sm-6 col-xs-12">
                {!! SodaForm::dropdown([
                    'name'         => 'Edit Action',
                    'field_name'   => 'edit_action_type',
                    'value'        => $model->type && $model->type->edit_action_type ? $model->type->edit_action_type : 'view',
                    'field_params' => ['options' => Soda::getPageBuilder()->getActionTypes()],
                ])->setModel($model)->setLayout(soda_cms_view_path('partials.inputs.layouts.inline-group')) !!}
            </div>
            <div class="col-sm-6 col-xs-12">
                {!! SodaForm::text([
                    'name'        => null,
                    'field_name'  => 'edit_action',
                    'value'       => $model->type && $model->type->edit_action ? $model->type->edit_action : '',
                ])->setModel($model)->setLayout(soda_cms_view_path('partials.inputs.layouts.inline-group')) !!}
            </div>
        </div>
    </div>
@stop

@section('content')
    <ul class="nav nav-tabs" role="tablist">
        <li role='presentation' aria-controls="Page Settings">
            <a role="tab" data-toggle="tab" href="#tab_page_settings">Settings</a>
        </li>

        @foreach($blocks as $block)
            @if($block->type->edit_action_type == 'view')
                <li role='presentation' aria-controls="block_{{ $block->id }}">
                    <a role="tab" data-toggle="tab" href="#tab_block_{{ $block->id }}">{{ $block->name }}</a>
                </li>
            @endif
        @endforeach

        @permission("live-preview")
        <li role='presentation' aria-controls="Live View">
            <a role="tab" data-toggle="tab" href="#tab_live">Live View</a>
        </li>
        @endpermission

        @permission("advanced-pages")
        <li role='presentation' aria-controls="Advanced View">
            <a role="tab" data-toggle="tab" href="#tab_advanced">Advanced</a>
        </li>
        @endpermission
    </ul>

    <form method="POST" id="page-form" action="{{ route('soda.' . $hint . ($model->id ? '.edit' : '.create'), ['id' => $model->id]) }}">
        {!! csrf_field() !!}
        @if($model->type)
            <input type="hidden" name="page_type_id" value="{{ $model->type->id }}"/>
        @endif
        <input type="hidden" name="parent_id" value="{{ $model->parent_id }}"/>
        <div class="tab-content">
            <div class="tab-pane" id="tab_page_settings" role="tabpanel">
                @yield('tab.settings')
            </div>
            @foreach($blocks as $block)
                @if($block->type->edit_action_type == 'view')
                    <div class="tab-pane" id="tab_block_{{ $block->id }}" role="tabpanel">
                        <div class="content-block">
                            @include($block->type->edit_action, [
                                'unique' => uniqid(),
                                'render' => 'card',
                                'block'  => $block,
                                'page'   => $model,
                                'models' => $model->blockModel($block)->paginate(null, ['*'], $block->identifier .'-page')
                            ])
                        </div>
                    </div>
                @endif
            @endforeach

            @permission("live-preview")
            <div class="tab-pane" id="tab_live" role="tabpanel">
                @yield('tab.live-preview')
            </div>
            @endpermission

            @permission("advanced-pages")
            <div class="tab-pane" id="tab_advanced" role="tabpanel">
                @yield('tab.advanced')
            </div>
            @endpermission
        </div>
    </form>

    <div class="content-bottom">
        @include(soda_cms_view_path('partials.buttons.save'), ['submits' => '#page-form'])
    </div>
@stop

@section('footer.js')
    @parent
    <script>
        <?php $active_set = false; ?>
        @foreach($model->blocks as $block)
            @if((Request::has('tab') && Request::input('tab') == $block->identifier) || Request::has($block->identifier . '-page'))
                <?php $active_set = true; ?>
                $('a[href="#tab_block_{{ $block->id }}"]').tab('show');
        @endif
    @endforeach
    @if(!$active_set)
        $('.nav-tabs a[data-toggle="tab"]').first().tab('show');
        @endif
    </script>
@stop
