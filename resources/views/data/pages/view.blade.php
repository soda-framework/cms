<?php
$smallView = false;
if ($page->getRelation('type') === null || $page->getRelation('type')->getRelation('fields') === null || !count($page->getRelation('type')->getRelation('fields')->where('pivot.show_in_table', 1))) {
    $smallView = true;
}
?>

@extends(soda_cms_view_path('layouts.inner'))

@section('breadcrumb')
    <ol class="breadcrumb">
        <li><a href="{{ route('soda.home') }}">Home</a></li>
        <li><a href="{{ route('soda.pages.index') }}">Pages</a></li>
        <li class="active">{{ $page->name ? $page->name : 'New ' . ($page->type ? $page->type->name . " Page" : "Page") }}</li>
    </ol>
@stop

@section('head.title')
    <title>Soda CMS | View Page</title>
@endsection

@section('content-heading-button')
    @include(soda_cms_view_path('partials.buttons.save'), ['submits' => '#page-form'])
@stop

@include(soda_cms_view_path('partials.heading'), [
    'icon'        => 'fa fa-pencil',
    'title'       => $page->name? ' Page: ' . $page->name : 'New Page',
    'description' => $page->description,
])

@section('tab.settings')
    <div class="row">
        @if(!$smallView)
        <div class="col-md-9 col-xs-12">
            <div class="content-block">
                @if($page->type->description)
                    <p>{{ $page->type->description }}</p>
                    <hr/>
                @endif
                @if($page->type && $page->type->fields)
                    @foreach($page->type->fields->where('pivot.show_in_table', 1) as $field)
                        {!! SodaForm::field($field)->setModel($page->pageAttributes())->setPrefix('settings') !!}
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
                ])->setLayout(soda_cms_view_path('partials.inputs.layouts.stacked'))->setModel($page) !!}

                {!! SodaForm::slug([
                    'name'        => 'Slug',
                    'description' => 'The url of this page',
                    'field_name'  => 'slug',
                    'field_params' => [
                        'prefix' => ($page->parent_id !== null && $parent = $page->getParent()) ? $parent->slug : '',
                    ],
                ])->setLayout(soda_cms_view_path('partials.inputs.layouts.stacked'))->setModel($page) !!}

                {!! SodaForm::toggle([
                    'name'         => 'Published',
                    'field_name'   => 'status',
                    'value'        => Soda\Cms\Support\Constants::STATUS_LIVE,
                    'field_params' => [
                        'checked-value'   => Soda\Cms\Support\Constants::STATUS_LIVE,
                        'unchecked-value' => Soda\Cms\Support\Constants::STATUS_DRAFT],
                ])->setLayout(soda_cms_view_path('partials.inputs.layouts.stacked'))->setModel($page) !!}
            </div>
        </div>
    </div>
@stop

@section('tab.live-preview')
    <div class="content-block">
        <p>Use this tab to customise information on the page in a live view</p>
        <hr/>
        @if($page->slug)
            <p>{{ $page->slug }}</p>
            <iframe width="100%" height=400 src="{{ $page->slug }}?soda_edit=true"></iframe>
        @else
            <p>You must set a slug to enabled this feature.</p>
        @endif
    </div>
@stop

@section('tab.advanced')
    <div class="content-block">
        <p>Advanced page settings</p>
        <hr/>

        <div class="row fieldset-group">
            <div class="col-sm-6 col-xs-12">
                {!! SodaForm::dropdown([
                    'name'        => 'View Action',
                    'field_name'  => 'view_action_type',
                    'field_params' => ['options' => app('soda.request-matcher')->getActionTypes()],
                    'description'  => 'Specifies the interface supplied when viewing this page.',
                ])->setModel($page)->setLayout(soda_cms_view_path('partials.inputs.layouts.inline-group')) !!}
            </div>
            <div class="col-sm-6 col-xs-12">
                {!! SodaForm::text([
                    'name'        => null,
                    'field_name'  => 'view_action',
                ])->setModel($page)->setLayout(soda_cms_view_path('partials.inputs.layouts.inline-group')) !!}
            </div>
        </div>

        <div class="row fieldset-group">
            <div class="col-sm-6 col-xs-12">
                {!! SodaForm::dropdown([
                    'name'        => 'Edit Action',
                    'field_name'  => 'edit_action_type',
                    'field_params' => ['options' => app('soda.request-matcher')->getActionTypes()],
                    'description'  => 'Specifies the interface supplied when editing this page.',

                ])->setModel($page)->setLayout(soda_cms_view_path('partials.inputs.layouts.inline-group')) !!}
            </div>
            <div class="col-sm-6 col-xs-12">
                {!! SodaForm::text([
                    'name'        => null,
                    'field_name'  => 'edit_action',
                ])->setModel($page)->setLayout(soda_cms_view_path('partials.inputs.layouts.inline-group')) !!}
            </div>
        </div>
    </div>
@stop

@section('content')
    <ul class="nav nav-tabs" role="tablist">
        <li role='presentation' aria-controls="Page Settings">
            <a role="tab" data-toggle="tab" href="#tab_page_settings">Settings</a>
        </li>

        @foreach($page->blocks as $block)
            @if($block->list_action_type == 'view')
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

    <form method="POST" id="page-form" action="{{ route('soda.pages.' . ($page->id ? 'update' : 'store'), $page->id) }}">
        {!! csrf_field() !!}
        {!! method_field($page->id ? 'PUT' : 'POST') !!}
        @if($page->type)
            <input type="hidden" name="page_type_id" value="{{ $page->type->id }}"/>
        @endif
        <input type="hidden" name="parent_id" value="{{ $page->parent_id }}"/>
        <div class="tab-content">
            <div class="tab-pane" id="tab_page_settings" role="tabpanel">
                @yield('tab.settings')
            </div>
            @foreach($page->blocks as $block)
                @if($block->list_action_type == 'view')
                    <div class="tab-pane" id="tab_block_{{ $block->id }}" role="tabpanel">
                        <div class="content-block">
                            @include($block->list_action, [
                                'block'  => $block,
                                'page'   => $page,
                                'models' => $page->blockModel($block)->paginate(null, ['*'], $block->identifier .'-page')
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
        @foreach($page->blocks as $block)
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
