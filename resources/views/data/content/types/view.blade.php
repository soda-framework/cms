@extends(soda_cms_view_path('layouts.inner'))

@section('content-heading-button')
    @include(soda_cms_view_path('partials.buttons.save'), ['submits' => '#content-type-form'])
@stop


@section('content')
    <ul class="nav nav-pills" role="tablist">
        <li role='presentation' aria-controls="tab_settings">
            <a role="tab" data-toggle="tab" href="#tab_settings">Settings</a>
        </li>

        <li role='presentation' aria-controls="tab_fields">
            <a role="tab" data-toggle="tab" href="#tab_fields">Fields</a>
        </li>

        <li role='presentation' aria-controls="tab_blocks">
            <a role="tab" data-toggle="tab" href="#tab_blocks">Blocks</a>
        </li>

        <li role='presentation' aria-controls="tab_advanced">
            <a role="tab" data-toggle="tab" href="#tab_advanced">Advanced</a>
        </li>
    </ul>

    <form method="POST" id="content-type-form"
          action="{{ route('soda.content-types.' . ($contentType->id ? 'update' : 'store'), $contentType->id) }}" enctype="multipart/form-data">
        <div class="tab-content">
            <div class="tab-pane" id="tab_settings" role="tabpanel">
                <div class="content-block">
                    {!! csrf_field() !!}
                    {!! method_field($contentType->id ? 'PUT' : 'POST') !!}

                    {!! app('soda.form')->text([
                        "name"        => 'Content Type Name',
                        "field_name"  => 'name',
                    ])->setModel($contentType) !!}

                    {!! app('soda.form')->textarea([
                        "name"        => "Content Type Description",
                        "field_name"  => 'description',
                    ])->setModel($contentType) !!}

                    {!! app('soda.form')->text([
                        "name"        => "Identifier",
                        "field_name"  => 'identifier',
                    ])->setModel($contentType) !!}
                </div>
            </div>

            <div class="tab-pane" id="tab_fields" role="tabpanel">
                <div class="content-block">
                    @if($contentType->id)
                        @if(count($contentType->fields))
                            <table class="table table-striped middle">
                                <thead>
                                <tr>
                                    <th width="20"></th>
                                    <th>Field</th>
                                    <th width="120">Show in table</th>
                                    <th width="232">Options</th>
                                </tr>
                                </thead>
                                <tbody class="sortable" data-entityname="content-types.fields">
                                @foreach($contentType->fields as $field)
                                    <tr data-itemId="{{ $field->id }}" data-parentId="{{ $contentType->id }}">
                                        <td class="sortable-handle"><img src="/soda/cms/img/drag-dots.gif" /></td>
                                        <td>{{ $field->name }}</td>
                                        <td>{{ $field->pivot->show_in_table ? 'Yes' : 'No' }}</td>
                                        <td>
                                            <a href='{{ route('soda.fields.edit', $field->id) }}' class='btn btn-warning' target="_blank"><span>Edit</span></a>
                                            <button type="button" class="btn btn-danger" data-detach="{{ route('soda.content-types.fields.detach', [$contentType->id, $field->id]) }}">
                                                Delete
                                            </button>

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
                        Please save the content type before managing fields.
                    @endif
                </div>
            </div>

            <div class="tab-pane" id="tab_blocks" role="tabpanel">
                <div class="content-block">
                    @if($contentType->id)
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

                            @foreach($contentType->blockTypes as $blockType)
                                <tr>
                                    <td>{{ $blockType->name }}</td>
                                    <td>{{ $blockType->description }}</td>
                                    <td>{{ $blockType->pivot->min_blocks }}</td>
                                    <td>{{ $blockType->pivot->max_blocks }}</td>
                                    <td>
                                        <button type="button" class="btn btn-danger" data-detach="{{ route('soda.content-types.blocks.detach', [$contentType->id, $blockType->id]) }}">
                                            Delete
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        @if(count($blockTypes))
                            <a class="btn btn-lg btn-success" href="#" data-add-block>Add Block</a>
                        @endif
                    @else
                        Please save the content type before managing blocks.
                    @endif
                </div>
            </div>

            <div class="tab-pane" id="tab_advanced" role="tabpanel">
                <div class="content-block">
                    {!! app('soda.form')->text([
                        'name'        => null,
                        'field_name'  => 'view_action',
                    ])->setModel($contentType)->setLayout(soda_cms_view_path('partials.inputs.layouts.stacked-group-addon')) !!}

                    {!! app('soda.form')->dropdown([
                        'name'        => 'View Action',
                        'field_name'  => 'view_action_type',
                        'field_params' => ['options' => Soda\Cms\Foundation\Constants::CONTENT_ACTION_TYPES],
                        'description'  => 'Specifies the interface supplied when viewing pages of this type.',
                    ])->setModel($contentType)->setLayout(soda_cms_view_path('partials.inputs.layouts.stacked-group')) !!}

                    {!! app('soda.form')->text([
                        'name'        => null,
                        'field_name'  => 'edit_action',
                    ])->setModel($contentType)->setLayout(soda_cms_view_path('partials.inputs.layouts.stacked-group-addon')) !!}

                    {!! app('soda.form')->dropdown([
                        'name'        => 'Edit Action',
                        'field_name'  => 'edit_action_type',
                        'field_params' => ['options' => Soda\Cms\Foundation\Constants::CONTENT_ACTION_TYPES],
                        'description'  => 'Specifies the interface supplied when editing content of this type.',

                    ])->setModel($contentType)->setLayout(soda_cms_view_path('partials.inputs.layouts.stacked-group')) !!}

                    <div class="row">
                        <div class="col-md-6 col-xs-12">
                            <table class="table middle table-settings">
                                <tbody>
                                <tr>
                                    <td>
                                        <label>Creatable</label><br />
                                        <small class="text-muted">If enabled, content of this type can be created from
                                                                  the CMS interface
                                        </small>
                                    </td>
                                    <td width="62">
                                        {!! $creatableFormItem = app('soda.form')->toggle([
                                            'name'         => null,
                                            'field_name'   => 'is_creatable',
                                            'value'        => 1,
                                            'field_params' => ['checked-value' => 1, 'unchecked-value' => 0],
                                        ])->setModel($contentType) !!}
                                    </td>
                                </tr>
                                <tr class="if_creatable">
                                    <td>
                                        <label>Sluggable</label><br />
                                        <small class="text-muted">If disabled, this content item can not be reached by a
                                                                  slug
                                        </small>
                                    </td>
                                    <td width="62">
                                        {!! app('soda.form')->toggle([
                                            'name'         => null,
                                            'field_name'   => 'is_sluggable',
                                            'value'        => 1,
                                            'field_params' => ['checked-value' => 1, 'unchecked-value' => 0],
                                        ])->setModel($contentType) !!}
                                    </td>
                                </tr>
                                <tr class="if_creatable">
                                    <td>
                                        <label>Publishable</label><br />
                                        <small class="text-muted">If disabled, this content item can not be changed from
                                                                  it's current published state
                                        </small>
                                    </td>
                                    <td width="62">
                                        {!! app('soda.form')->toggle([
                                            'name'         => null,
                                            'field_name'   => 'is_publishable',
                                            'value'        => 1,
                                            'field_params' => ['checked-value' => 1, 'unchecked-value' => 0],
                                        ])->setModel($contentType) !!}
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-6 col-xs-12">
                            <table class="table middle table-settings">
                                <tbody>
                                <tr class="if_creatable">
                                    <td>
                                        <label>Movable</label><br />
                                        <small class="text-muted">If disabled, this content item can not be moved
                                        </small>
                                    </td>
                                    <td width="62">
                                        {!! app('soda.form')->toggle([
                                            'name'         => null,
                                            'field_name'   => 'is_movable',
                                            'value'        => 1,
                                            'field_params' => ['checked-value' => 1, 'unchecked-value' => 0],
                                        ])->setModel($contentType) !!}
                                    </td>
                                </tr>
                                <tr class="if_creatable">
                                    <td>
                                        <label>Folder</label><br />
                                        <small class="text-muted">If enabled, this content can have child content
                                                                  items
                                        </small>
                                    </td>
                                    <td width="62">
                                        {!! $allowedChildrenFormItem = app('soda.form')->toggle([
                                            'name'         => null,
                                            'field_name'   => 'is_folder',
                                            'value'        => 0,
                                            'field_params' => ['checked-value' => 1, 'unchecked-value' => 0],
                                        ])->setModel($contentType) !!}
                                    </td>
                                </tr>
                                <tr class="restricted_page_types">
                                    <td>
                                        <label>Restrict Child Content Types</label><br />
                                        <small class="text-muted">If enabled, you may select which content types may be
                                                                  created as a child of this folder
                                        </small>
                                    </td>
                                    <td>
                                        {!! $restrictePageTypesFormItem = app('soda.form')->toggle([
                                            'name'         => null,
                                            'field_name'   => 'page_types_restricted',
                                            'value'        => !empty($subpageIds),
                                            'field_params' => ['checked-value' => 1, 'unchecked-value' => 0],
                                        ])->setModel($contentType) !!}
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>


                    <div class="allowed_subpage_types">
                        <label>Restricted Content Types</label>
                        <table class="table well">
                            <thead>
                            <tr>
                                <th>Name</th>
                                <th>Description</th>
                                <th width="80">Enabled</th>
                            </tr>
                            </thead>
                            <tbody>

                            @foreach($contentTypes as $subPageType)
                                <tr>
                                    <td>{{ $subPageType->name }}</td>
                                    <td>{{ $subPageType->description }}</td>
                                    <td>
                                        {!! app('soda.form')->toggle([
                                            'field_name'   => $subPageType->id,
                                            'value'        => empty($subpageIds) || in_array($subPageType->id, $subpageIds),
                                            'field_params' => ['checked-value' => 1, 'unchecked-value' => 0],
                                        ])->setModel($contentType)->setPrefix('content_types') !!}
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <div class="content-bottom">
        @include(soda_cms_view_path('partials.buttons.save'), ['submits' => '#content-type-form'])
    </div>
@stop

@section('modals')
    @parent


    @if($contentType->id)
        <div class="modal fade" id="newBlockModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                    aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">Select a block to add...</h4>
                    </div>
                    <form method="POST" action="{{ route('soda.content-types.blocks.attach', $contentType->id) }}">
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
                            <button class="btn btn-primary">Add Block</button>
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
                    <form method="POST" action="{{ route('soda.content-types.fields.attach', $contentType->id) }}">
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
                                "description" => "Determines whether field value is shown in table view when listing content of this type"
                            ])->setLayout(soda_cms_view_path('partials.inputs.layouts.stacked')) !!}
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button class="btn btn-primary">Add Field</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
@stop

@section('footer.js')
    @parent
    <script src="/soda/cms/js/forms/sortable.js"></script>
    <script>
        $(function () {
            var creatableFormItem = $('#{{ $creatableFormItem->getFieldId() }}');
            var isFolderFormItem = $('#{{ $allowedChildrenFormItem->getFieldId() }}');
            var restrictedTypesFormItem = $('#{{ $restrictePageTypesFormItem->getFieldId() }}');

            creatableFormItem.on('change', function () {
                if ($(this).is(":checked")) {
                    $('.if_creatable').fadeIn();
                } else {
                    $('.if_creatable').fadeOut();
                }

                isFolderFormItem.trigger('change');
            });

            restrictedTypesFormItem.on('change', function () {
                if (creatableFormItem.is(':checked') && isFolderFormItem.is(':checked') && $(this).is(":checked")) {
                    $('.allowed_subpage_types').fadeIn();
                } else {
                    $('.allowed_subpage_types').fadeOut();
                }
            });

            isFolderFormItem.on('change', function () {
                if (creatableFormItem.is(':checked') && $(this).is(":checked")) {
                    $('.restricted_page_types').fadeIn();
                } else {
                    $('.restricted_page_types').fadeOut();
                }

                restrictedTypesFormItem.trigger('change');
            })

            creatableFormItem.trigger('change');

            $('[data-add-block]').on('click', function (e) {
                e.preventDefault();
                $('#newBlockModal').modal('show')
            })

            $('[data-add-field]').on('click', function (e) {
                e.preventDefault();
                $('#newFieldModal').modal('show')
            })

            $('[data-detach]').on('click', function (e) {
                e.preventDefault();
                var form = $('<form></form>');
                var parameters = {
                    '_method': 'DELETE',
                    '_token': '{{ csrf_token() }}',
                }

                form.attr("method", "POST");
                form.attr("action", $(this).data('detach'));

                $.each(parameters, function (key, value) {
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
                update: function (a, b) {

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
