@extends(soda_cms_view_path('layouts.inner'))

@section('content-heading-button')
    @include(soda_cms_view_path('partials.buttons.save'), ['submits' => '#page-type-form'])
@stop


@section('content')
    <ul class="nav nav-tabs" role="tablist">
        <li role='presentation' aria-controls="tab_settings">
            <a role="tab" data-toggle="tab" href="#tab_settings">Settings</a>
        </li>

        <li role='presentation' aria-controls="tab_fields">
            <a role="tab" data-toggle="tab" href="#tab_fields">Fields</a>
        </li>

        <li role='presentation' aria-controls="tab_subpages">
            <a role="tab" data-toggle="tab" href="#tab_subpages">Subpages</a>
        </li>

        <li role='presentation' aria-controls="tab_blocks">
            <a role="tab" data-toggle="tab" href="#tab_blocks">Blocks</a>
        </li>

        <li role='presentation' aria-controls="tab_advanced">
            <a role="tab" data-toggle="tab" href="#tab_advanced">Advanced</a>
        </li>
    </ul>

    <form method="POST" id="page-type-form"
          action="{{ route('soda.page-types.' . ($pageType->id ? 'update' : 'store'), $pageType->id) }}" enctype="multipart/form-data">
        <div class="tab-content">
            <div class="tab-pane" id="tab_settings" role="tabpanel">
                <div class="content-block">
                    {!! csrf_field() !!}
                    {!! method_field($pageType->id ? 'PUT' : 'POST') !!}

                    {!! app('soda.form')->text([
                        "name"        => 'Page Type Name',
                        "field_name"  => 'name',
                    ])->setModel($pageType) !!}

                    {!! app('soda.form')->textarea([
                        "name"        => "Page Type Description",
                        "field_name"  => 'description',
                    ])->setModel($pageType) !!}

                    {!! app('soda.form')->text([
                        "name"        => "Identifier",
                        "field_name"  => 'identifier',
                    ])->setModel($pageType) !!}

                    {!! app('soda.form')->toggle([
                        'name'         => 'Can Create',
                        'field_name'   => 'can_create',
                        'value'        => 1,
                        'field_params' => ['checked-value' => 1, 'unchecked-value' => 0],
                        'description'  => 'If enabled, pages of this type can be created from the CMS interface'
                    ])->setModel($pageType) !!}
                </div>
            </div>

            <div class="tab-pane" id="tab_fields" role="tabpanel">
                <div class="content-block">
                    @if($pageType->id)
                        @if(count($pageType->fields))
                            <table class="table">
                                <thead>
                                <tr>
                                    <th width="20"></th>
                                    <th>Field</th>
                                    <th width="120">Show in table</th>
                                    <th width="192">Options</th>
                                </tr>
                                </thead>
                                <tbody class="sortable" data-entityname="page-types.fields">
                                    @foreach($pageType->fields as $field)
                                        <tr data-itemId="{{ $field->id }}" data-parentId="{{ $pageType->id }}">
                                            <td class="sortable-handle"><img src="/soda/cms/img/drag-dots.gif" /></td>
                                            <td>{{ $field->name }}</td>
                                            <td>{{ $field->pivot->show_in_table ? 'Yes' : 'No' }}</td>
                                            <td>
                                                <a href='{{ route('soda.fields.edit', $field->id) }}' class='btn btn-warning' target="_blank"><span>Edit</span></a>
                                                <button type="button" class="btn btn-danger" data-detach="{{ route('soda.page-types.fields.detach', [$pageType->id, $field->id]) }}">Delete</button>

                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <p>No fields to show</p>
                        @endif
                        @if(count($fields))
                        <a class="btn btn-lg btn-success" href="#" data-add-field>Add Field</a>
                        @endif
                    @else
                        Please save the page type before managing fields.
                    @endif
                </div>
            </div>

            <div class="tab-pane" id="tab_subpages" role="tabpanel">
                <div class="content-block">
                    {!! $allowedChildrenFormItem = app('soda.form')->toggle([
                        'name'         => 'Allowed Subpages',
                        'field_name'   => 'allowed_children',
                        'value'        => 1,
                        'field_params' => ['checked-value' => 1, 'unchecked-value' => 0],
                        'description'  => 'If enabled, pages of this type can have child pages'
                    ])->setModel($pageType) !!}

                    <?php
                    $subpageIds = $pageType->subpageTypes->pluck('id')->toArray();
                    ?>

                    <div class="restricted_page_types" style="display:none">
                        {!! $restrictePageTypesFormItem = app('soda.form')->toggle([
                            'name'         => 'Restrict Allowed Page Types',
                            'field_name'   => 'page_types_restricted',
                            'value'        => !empty($subpageIds),
                            'field_params' => ['checked-value' => 1, 'unchecked-value' => 0],
                            'description'  => 'If enabled, you may select what page types may be created as a child of this type'
                        ])->setModel($pageType) !!}

                        <div class="allowed_subpage_types">
                            <label>Restricted Page Types</label>
                            <table class="table well">
                                <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Description</th>
                                    <th width="80">Enabled</th>
                                </tr>
                                </thead>
                                <tbody>

                                @foreach($pageTypes as $subPageType)
                                    <tr>
                                        <td>{{ $subPageType->name }}</td>
                                        <td>{{ $subPageType->description }}</td>
                                        <td>
                                            {!! app('soda.form')->toggle([
                                                'field_name'   => $subPageType->id,
                                                'value'        => empty($subpageIds) || in_array($subPageType->id, $subpageIds),
                                                'field_params' => ['checked-value' => 1, 'unchecked-value' => 0],
                                            ])->setModel($pageType)->setPrefix('subpage_types') !!}
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tab-pane" id="tab_blocks" role="tabpanel">
                <div class="content-block">
                    @if($pageType->id)
                    <table class="table">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Min Blocks</th>
                            <th>Max Blocks</th>
                            <th width="80">Options</th>
                        </tr>
                        </thead>
                        <tbody>

                        @foreach($pageType->blockTypes as $blockType)
                            <tr>
                                <td>{{ $blockType->name }}</td>
                                <td>{{ $blockType->description }}</td>
                                <td>{{ $blockType->pivot->min_blocks }}</td>
                                <td>{{ $blockType->pivot->max_blocks }}</td>
                                <td>
                                    <button type="button" class="btn btn-danger" data-detach="{{ route('soda.page-types.blocks.detach', [$pageType->id, $blockType->id]) }}">Delete</button>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                        @if(count($blockTypes))
                            <a class="btn btn-lg btn-success" href="#" data-add-block>Add Block</a>
                        @endif
                    @else
                        Please save the page type before managing blocks.
                    @endif
                </div>
            </div>

            <div class="tab-pane" id="tab_advanced" role="tabpanel">
                <div class="content-block">
                    {!! app('soda.form')->text([
                        'name'        => null,
                        'field_name'  => 'view_action',
                    ])->setModel($pageType)->setLayout(soda_cms_view_path('partials.inputs.layouts.stacked-group-addon')) !!}

                    {!! app('soda.form')->dropdown([
                        'name'        => 'View Action',
                        'field_name'  => 'view_action_type',
                        'field_params' => ['options' => Soda\Cms\Foundation\Constants::PAGE_ACTION_TYPES],
                        'description'  => 'Specifies the interface supplied when viewing pages of this type.',
                    ])->setModel($pageType)->setLayout(soda_cms_view_path('partials.inputs.layouts.stacked-group')) !!}

                    {!! app('soda.form')->text([
                        'name'        => null,
                        'field_name'  => 'edit_action',
                    ])->setModel($pageType)->setLayout(soda_cms_view_path('partials.inputs.layouts.stacked-group-addon')) !!}

                    {!! app('soda.form')->dropdown([
                        'name'        => 'Edit Action',
                        'field_name'  => 'edit_action_type',
                        'field_params' => ['options' => Soda\Cms\Foundation\Constants::PAGE_ACTION_TYPES],
                        'description'  => 'Specifies the interface supplied when editing pages of this type.',

                    ])->setModel($pageType)->setLayout(soda_cms_view_path('partials.inputs.layouts.stacked-group')) !!}
                </div>
            </div>
        </div>
    </form>

    <div class="content-bottom">
        @include(soda_cms_view_path('partials.buttons.save'), ['submits' => '#page-type-form'])
    </div>
@stop

@section('modals')
    @parent


    @if($pageType->id)
        <div class="modal fade" id="newBlockModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                    aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">Select a block to add...</h4>
                    </div>
                    <form method="POST" action="{{ route('soda.page-types.blocks.attach', $pageType->id) }}">
                        {!! csrf_field() !!}
                        <div class="modal-body">
                            {!! app('soda.form')->dropdown([
                                "name"         => "Blocks",
                                "field_name"   => 'block_id',
                                "field_params" => ['options' => $blockTypes->pluck('name', 'id')]
                            ])->setLayout(soda_cms_view_path('partials.inputs.layouts.stacked')) !!}

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
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button  class="btn btn-primary">Add Block</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal fade" id="newFieldModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                    aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">Select a field to add...</h4>
                    </div>
                    <form method="POST" action="{{ route('soda.page-types.fields.attach', $pageType->id) }}">
                        {!! csrf_field() !!}
                        <div class="modal-body">
                            {!! app('soda.form')->dropdown([
                                "name"         => "Fields",
                                "field_name"   => 'fieldable_id',
                                "field_params" => ['options' => $fields->pluck('name', 'id')]
                            ])->setLayout(soda_cms_view_path('partials.inputs.layouts.stacked')) !!}

                            {!! app('soda.form')->toggle([
                                "name"        => "Show in table?",
                                "field_name"  => "show_in_table",
                                "value"       => 1,
                                "description" => "Determines whether field value is shown in table view when listing pages of this type"
                            ])->setLayout(soda_cms_view_path('partials.inputs.layouts.stacked')) !!}
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button  class="btn btn-primary">Add Field</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
@stop

@section('footer.js')
    @parent
    <script>
        $(function() {
            $('#{{ $allowedChildrenFormItem->getFieldId() }}').on('change', function() {
                if($(this).is(":checked"))
                {
                    $('.restricted_page_types').fadeIn();
                } else {
                    $('.restricted_page_types').fadeOut();
                }
            }).trigger('change');

            $('#{{ $restrictePageTypesFormItem->getFieldId() }}').on('change', function() {
                if($(this).is(":checked"))
                {
                    $('.allowed_subpage_types').fadeIn();
                } else {
                    $('.allowed_subpage_types').fadeOut();
                }
            }).trigger('change');

            $('[data-add-block]').on('click', function(e) {
                e.preventDefault();
                $('#newBlockModal').modal('show')
            })

            $('[data-add-field]').on('click', function(e) {
                e.preventDefault();
                $('#newFieldModal').modal('show')
            })

            $('[data-detach]').on('click', function(e) {
                e.preventDefault();
                var form = $('<form></form>');
                var parameters = {
                    '_method': 'DELETE',
                    '_token': '{{ csrf_token() }}',
                }

                form.attr("method", "POST");
                form.attr("action", $(this).data('detach'));

                $.each(parameters, function(key, value) {
                    var field = $('<input></input>');

                    field.attr("type", "hidden");
                    field.attr("name", key);
                    field.attr("value", value);

                    form.append(field);
                });

                // The form needs to be a part of the document in
                // order for us to be able to submit it.
                $(document.body).append(form);
                form.submit();
            });

            $('.sortable').sortable({
                handle: '.sortable-handle',
                axis: 'y',
                update: function(a, b){

                    var $sorted = b.item;

                    var $previous = $sorted.prev();
                    var $next = $sorted.next();

                    var data = {
                        id: $sorted.data('itemid'),
                        parentId: $sorted.data('parentid'),
                        entityName: $(this).data('entityname'),
                    };

                    if ($previous.length > 0) {
                        data.type = 'moveAfter';
                        data.positionEntityId = $previous.data('itemid');

                        console.log(data);
                        Soda.changePosition(data);
                    } else if ($next.length > 0) {
                        data.type = 'moveBefore';
                        data.positionEntityId = $next.data('itemid');

                        console.log(data);
                        Soda.changePosition(data);
                    } else {
                        console.error('Something wrong!');
                    }
                },
                cursor: "move"
            });
        });
    </script>
@stop
