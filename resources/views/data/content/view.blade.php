<?php

$blockTypes = $content->blockTypes->keyBy('id');
if ($content->type && $content->type->blockTypes) {
    $blockTypes = $blockTypes->merge($content->type->blockTypes->keyBy('id'));
}
?>

@extends(soda_cms_view_path('layouts.inner'))

@section('content-heading-button')
    @include(soda_cms_view_path('partials.buttons.save'), ['submits' => '#page-form'])

    @if($content->is_sluggable && (!$content->is_publishable || $content->isPublished() || Session::get("soda.draft_mode") == true))
        <a class="btn btn-info btn-lg" href="{{ URL::to($content->slug) }}" target="_blank">
            <span>View Content</span>
        </a>
    @endif
@stop

@section('settings.basic')
    {!! app('soda.form')->text([
        "name"        => "Name",
        "description" => "The name of this content item",
        "field_name"  => 'name',
    ])->setModel($content) !!}

    @if(!$content->exists || $content->isSluggable())
        {!! app('soda.form')->slug([
            'name'        => 'Slug',
            'description' => 'The url of this content item',
            'field_name'  => 'slug',
            'field_params' => [
                'prefix' => ($content->parent_id !== null && $parent = $content->getParent()) ? $parent->slug : '',
            ],
        ])->setModel($content) !!}
    @endif

    <div class="if-publishable" {!! $content->is_publishable ? '' : 'style="display:none"' !!}>
        {!! app('soda.form')->toggle([
            'name'         => 'Published',
            'field_name'   => 'status',
            'value'        => Soda\Cms\Foundation\Constants::STATUS_LIVE,
            'field_params' => [
                'checked-value'   => Soda\Cms\Foundation\Constants::STATUS_LIVE,
                'unchecked-value' => Soda\Cms\Foundation\Constants::STATUS_DRAFT],
        ])->setModel($content) !!}
    </div>
@stop

@section('tab.advanced')
    <div class="content-block">
        <p>Advanced page settings</p>
        <hr />

        <div class="row">
            <div class="col-md-6 col-xs-12">
                <table class="table middle table-settings">
                    <tbody>
                    <tr>
                        <td>
                            <label>Sluggable</label><br />
                            <small class="text-muted">If disabled, this content item can not be reached by a slug
                            </small>
                        </td>
                        <td width="62">
                            {!! $isSluggableFormItem = app('soda.form')->toggle([
                                'name'         => null,
                                'field_name'   => 'is_sluggable',
                                'value'        => 1,
                                'field_params' => ['checked-value' => 1, 'unchecked-value' => 0],
                            ])->setModel($content) !!}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label>Publishable</label><br />
                            <small class="text-muted">If disabled, this content item can not be changed from it's
                                                      current published state
                            </small>
                        </td>
                        <td width="62">
                            {!! $isPublishableFormItem = app('soda.form')->toggle([
                                'name'         => null,
                                'field_name'   => 'is_publishable',
                                'value'        => 1,
                                'field_params' => ['checked-value' => 1, 'unchecked-value' => 0],
                            ])->setModel($content) !!}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label>Folder</label><br />
                            <small class="text-muted">If enabled, this content can have child content items</small>
                        </td>
                        <td width="62">
                            {!! app('soda.form')->toggle([
                                'name'         => null,
                                'field_name'   => 'is_folder',
                                'value'        => 0,
                                'field_params' => ['checked-value' => 1, 'unchecked-value' => 0],
                            ])->setModel($content) !!}
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div class="col-md-6 col-xs-12">
                <table class="table middle table-settings">
                    <tbody>
                    <tr>
                        <td>
                            <label>Deletable</label><br />
                            <small class="text-muted">If disabled, this content item can not be deleted</small>
                        </td>
                        <td width="62">
                            {!! app('soda.form')->toggle([
                                'name'         => null,
                                'field_name'   => 'is_deletable',
                                'value'        => 1,
                                'field_params' => ['checked-value' => 1, 'unchecked-value' => 0],
                            ])->setModel($content) !!}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label>Movable</label><br />
                            <small class="text-muted">If disabled, this content item can not be moved</small>
                        </td>
                        <td width="62">
                            {!! app('soda.form')->toggle([
                                'name'         => null,
                                'field_name'   => 'is_movable',
                                'value'        => 1,
                                'field_params' => ['checked-value' => 1, 'unchecked-value' => 0],
                            ])->setModel($content) !!}
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="if-sluggable">
            {!! app('soda.form')->text([
                'name'        => null,
                'field_name'  => 'view_action',
                'value'       => $content->type && $content->type->view_action,
            ])->setModel($content)->setLayout(soda_cms_view_path('partials.inputs.layouts.stacked-group-addon')) !!}

            {!! app('soda.form')->dropdown([
                'name'        => 'View Action',
                'field_name'  => 'view_action_type',
                'field_params' => ['options' => Soda\Cms\Foundation\Constants::CONTENT_ACTION_TYPES],
                'description'  => 'Specifies the interface supplied when viewing this page.',
                'value'        => $content->type && $content->type->view_action_type ? $content->type->view_action_type : 'view',
            ])->setModel($content)->setLayout(soda_cms_view_path('partials.inputs.layouts.stacked-group')) !!}

            {!! app('soda.form')->text([
                'name'        => null,
                'field_name'  => 'edit_action',
                'value'       => $content->type && $content->type->edit_action,
            ])->setModel($content)->setLayout(soda_cms_view_path('partials.inputs.layouts.stacked-group-addon')) !!}

            {!! app('soda.form')->dropdown([
                'name'        => 'Edit Action',
                'field_name'  => 'edit_action_type',
                'field_params' => ['options' => Soda\Cms\Foundation\Constants::CONTENT_ACTION_TYPES],
                'description'  => 'Specifies the interface supplied when editing this page.',
                'value'        => $content->type && $content->type->edit_action_type ? $content->type->edit_action_type : 'view',
            ])->setModel($content)->setLayout(soda_cms_view_path('partials.inputs.layouts.stacked-group')) !!}
        </div>
    </div>
@stop

@section('content')
    <form method="POST" id="page-form" action="{{ route('soda.content.' . ($content->id ? 'update' : 'store'), $content->id) }}"
          enctype="multipart/form-data">
        {!! csrf_field() !!}
        {!! method_field($content->id ? 'PUT' : 'POST') !!}
        @if($content->type)
            <input type="hidden" name="content_type_id" value="{{ $content->type->id }}" />
        @endif
        @if(!$content->id)
            <input type="hidden" name="parent_id" value="{{ $content->parent_id }}" />
        @endif
        <div class="row">
            <div class="col-xs-12">

                <ul class="nav nav-pills" role="tablist">
                    <li role='presentation' aria-controls="tab_settings">
                        <a role="tab" data-toggle="pill" href="#tab_settings">Settings</a>
                    </li>

                    @foreach($blockTypes as $blockType)
                        @if($blockType->list_action_type == 'view')
                            <li role='presentation' aria-controls="tab_{{ strtolower($blockType->identifier) }}">
                                <a role="tab" data-toggle="pill"
                                   href="#tab_{{ strtolower($blockType->identifier) }}">{{ $blockType->name }}</a>
                            </li>
                        @endif
                    @endforeach

                    @permission("advanced-pages")
                    <li role='presentation' aria-controls="tab_advanced">
                        <a role="tab" data-toggle="pill" href="#tab_advanced">Advanced</a>
                    </li>
                    @endpermission

                    @if($content->id && count($blockTypes))
                    @permission("attach-blocks")
                        <li role='presentation'>
                            <a role="tab" href="#tab_new-block">+</a>
                        </li>
                    @endpermission
                    @endif
                </ul>
                <div class="tab-content">
                    <div class="tab-pane" id="tab_settings" role="tabpanel">
                        <div class="content-block">
                            @yield('settings.basic')

                            @if($content->type)
                                <hr />
                                @if($content->type->fields)
                                    @foreach($content->type->fields as $field)
                                        @if($field->pivot->show_in_table == 1)
                                            {!! app('soda.form')->field($field)->setModel($content->properties)->setPrefix('settings') !!}
                                        @elseif($field->pivot->show_in_table == 2)
                                            {!! app('soda.form')->staticText()->setField($field)->setModel($content->properties)->setPrefix('settings') !!}
                                        @endif
                                    @endforeach
                                @endif
                            @endif
                        </div>
                    </div>
                    @foreach($blockTypes as $blockType)
                        @if($blockType->list_action_type == 'view')
                            <div class="tab-pane" id="tab_{{ strtolower($blockType->identifier) }}" role="tabpanel">
                                <div class="content-block">
                                    @if($content->id)
                                        @include($blockType->list_action, [
                                            'blockType'  => $blockType,
                                            'page'       => $content,
                                            'blocks'     => $content->block($blockType)->paginate(null, ['*'], $blockType->identifier .'-page')->appends(['tab' => $blockType->identifier])
                                        ])
                                    @else
                                        <p>Content must be saved before creating blocks.</p>
                                    @endif
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
            </div>
        </div>
    </form>

    <div class="content-bottom">
        @include(soda_cms_view_path('partials.buttons.save'), ['submits' => '#page-form'])

        @if($content->id && $content->is_sluggable && (!$content->is_publishable || $content->isPublished() || Session::get("soda.draft_mode") == true))
            <a class="btn btn-info btn-lg" href="{{ URL::to($content->slug) }}" target="_blank">
                <span>View Content</span>
            </a>
        @endif
    </div>
@stop

@section('modals')
    @parent
    @if($content->id)
        @permission("attach-blocks")
        <div class="modal fade" id="newBlockModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                    aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">Select a block to add...</h4>
                    </div>
                    <form method="POST" action="{{ route('soda.content.blocks.attach', $content->id) }}">
                        {!! csrf_field() !!}
                        <div class="modal-body">
                            {!! app('soda.form')->dropdown([
                                "name"         => "Blocks",
                                "field_name"   => 'block_id',
                                "field_params" => ['options' => $blockTypes->pluck('name', 'id')]
                            ])->setLayout(soda_cms_view_path('partials.inputs.layouts.stacked')) !!}

                            @permission("manage-block-types")
                            {!! app('soda.form')->text([
                                "name"        => "Max Blocks",
                                "field_name"  => "max_blocks",
                                "description" => "Defines the maximum number of rows this block can hold. Default: unlimited"
                            ])->setLayout(soda_cms_view_path('partials.inputs.layouts.stacked')) !!}

                            {!! app('soda.form')->text([
                                "name"        => "Min Blocks",
                                "field_name"  => "min_blocks",
                                "description" => "Defines the minimum number of rows this block can hold. Default: unlimited"
                            ])->setLayout(soda_cms_view_path('partials.inputs.layouts.stacked')) !!}
                            @endpermission
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button class="btn btn-primary">Add Block</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @endpermission
    @endif
@stop

@section('footer.js')
    @parent
    <script>
        $(function () {
            var isSluggableFormItem = $('#{{ $isSluggableFormItem->getFieldId() }}');
            var isPublishableFormItem = $('#{{ $isPublishableFormItem->getFieldId() }}');

            isSluggableFormItem.on('change', function () {
                if ($(this).is(":checked")) {
                    $('.if-sluggable').fadeIn();
                } else {
                    $('.if-sluggable').fadeOut();
                }
            }).trigger('change');

            isPublishableFormItem.on('change', function () {
                if ($(this).is(":checked")) {
                    $('.if-publishable').fadeIn();
                } else {
                    $('.if-publishable').fadeOut();
                }
            }).trigger('change');

            $('a[href="#tab_new-block"]').on('click', function (e) {
                e.preventDefault();
                $('#newBlockModal').modal('show')
            })
        });
    </script>
@stop
