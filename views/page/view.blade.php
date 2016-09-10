<?php
$small_view = false;
if((!$model->type || !count($model->type->fields)) && !count($model->blocks) && !Auth::user()->can('live-preview') && !Auth::user()->can('advanced-pages')) {
    $small_view = true;
}
?>

@extends(soda_cms_view_path($small_view ? 'layouts.inner' : 'layouts.inner-sidebar'))

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

@include(soda_cms_view_path('partials.heading'), [
    'icon'        => 'fa fa-file-o',
    'title'       => $model->name? ' Page: ' . $model->name : 'New Page',
    'description' => $model->description,
])

@section('page.main')
    <form method="POST" action="{{ route('soda.' . $hint . ($model->id ? '.edit' : '.create'), ['id' => $model->id]) }}">
        {!! csrf_field() !!}
        @if($model->type)
            <input type="hidden" name="page_type_id" value="{{ $model->type->id }}" />
        @endif
        <input type="hidden" name="parent_id" value="{{ $model->parent_id }}" />

        {!! SodaForm::text([
            "name"        => "Name",
            "description" => "The name of this page",
            "field_name"  => 'name',
        ])->setLayout('soda::partials.inputs.layouts.stacked')->setModel($model) !!}

        {!! SodaForm::text([
            'name'        => 'Slug',
            'description' => 'The url of this page',
            'field_name'  => 'slug',
        ])->setLayout('soda::partials.inputs.layouts.stacked')->setModel($model) !!}

        {!! SodaForm::toggle([
            'name'         => 'Published',
            'field_name'   => 'status',
            'value'        => Soda\Cms\Components\Status::LIVE,
            'field_params' => ['checked-value' => Soda\Cms\Components\Status::LIVE, 'unchecked-value' => Soda\Cms\Components\Status::DRAFT],
        ])->setLayout('soda::partials.inputs.layouts.stacked')->setModel($model) !!}
        <br />

        <input class="btn btn-success" type="submit" value="Save" />
    </form>
@stop

@section('content.sidebar')
    @if(!$small_view)
        @yield('page.main')
    @endif
@stop

@section('content')
    @if($small_view)
        @yield('page.main')
    @else
    <ul class="nav nav-tabs" role="tablist">
        @if($model->type && count($model->type->fields))
            <li role='presentation' aria-controls="page_type_{{ $model->type->id }}">
                <a role="tab" data-toggle="tab" href="#tab_page_type_{{ $model->type->id }}">{{ $model->type->name }}</a>
            </li>
        @endif
        @foreach($model->blocks as $block)
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

    <form method="POST" action="{{ route('soda.' . $hint . ($model->id ? '.edit' : '.create'), ['id' => $model->id]) }}">
        {!! csrf_field() !!}
        @if($model->type)
        <input type="hidden" name="page_type_id" value="{{ $model->type->id }}" />
        @endif
        <input type="hidden" name="parent_id" value="{{ $model->parent_id }}" />
        <div class="tab-content">
            @if($model->type)
                <div class="tab-pane" id="tab_page_type_{{ $model->type->id }}" role="tabpanel">
                    <h3>{{ $model->type->name }}</h3>
                    <p>{{ $model->type->description }}</p>
                    <hr />
                    @if($model->type && $model->type->fields)
                        @foreach($model->type->fields as $field)
                            {!! SodaForm::field($field)->setModel(@$page_table)->setPrefix('settings') !!}
                        @endforeach
                    @endif

                    <input class="btn btn-success" type="submit" value="Save" />
                </div>
            @endif
            @foreach($model->blocks as $block)
                @if($block->type->edit_action_type == 'view')
                    <div class="tab-pane" id="tab_block_{{ $block->id }}" role="tabpanel">
                        @include($block->type->edit_action, [
                            'unique' => uniqid(),
                            'render' => 'card',
                            'block'  => $block,
                            'page'   => $model,
                            'models' => $model->blockModel($block)->paginate(null, ['*'], $block->identifier .'-page')
                        ])
                    </div>
                @endif
                {{--loads a block into place.. --}}
            @endforeach

            @permission("live-preview")
            <div class="tab-pane" id="tab_live" role="tabpanel">
                <h3>Settings</h3>
                <p>Use this tab to customise information on the page in a live view</p>
                <hr />
                @if($model->slug)
                    <p>{{ $model->slug }}</p>
                    <iframe width="100%" height=400 src="{{ $model->slug }}?soda_edit=true"></iframe>
                @else
                    <p>You must set a slug to enabled this feature.</p>
                @endif
            </div>
            @endpermission

            @permission("advanced-pages")
            <div class="tab-pane" id="tab_advanced" role="tabpanel">
                <h3>Settings</h3>
                <p>Advanced page settings</p>
                <hr />

                {!! SodaForm::text([
                    'name'        => 'Package Name',
                    'field_name'  => 'package',
                ])->setModel($model) !!}

                {!! SodaForm::text([
                    'name'        => 'Action',
                    'field_name'  => 'action',
                ])->setModel($model) !!}

                {!! SodaForm::text([
                    'name'        => 'Action Type',
                    'field_name'  => 'action_type',
                ])->setModel($model) !!}

                {!! SodaForm::text([
                    'name'        => 'Edit Action',
                    'field_name'  => 'edit_action',
                ])->setModel($model) !!}

                {!! SodaForm::text([
                    'name'        => 'Edit Action Type',
                    'field_name'  => 'edit_action_type',
                ])->setModel($model) !!}

                <input class="btn btn-success" type="submit" value="Save" />
            </div>
            @endpermission
        </div>
    </form>
    @endif
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
