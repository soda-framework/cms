@extends(soda_cms_view_path('layouts.inner'))

@section('breadcrumb')
    <li><a href="{{ route('soda.home') }}">Home</a></li>
    <li><a href="{{ route('soda.block-types.index') }}">Block Types</a></li>
    <li class="active">{{ $blockType->name ? $blockType->name : 'New Block Type' }}</li>
@stop

@section('head.title')
    <title>{{ $blockType->id ? 'Edit' : 'New' }} Block Type :: Soda CMS</title>
@endsection

@section('content-heading-button')
    @include(soda_cms_view_path('partials.buttons.save'), ['submits' => '#block-type-form'])
@stop

@include(soda_cms_view_path('partials.heading'), [
    'icon'        => 'fa fa-widgets',
    'title'       => $blockType->name ? 'Block Type: ' . $blockType->name : 'New Block Type',
])

@section('content')
    <ul class="nav nav-tabs" role="tablist">
        <li role='presentation' aria-controls="tab_settings">
            <a role="tab" data-toggle="tab" href="#tab_settings">Settings</a>
        </li>

        <li role='presentation' aria-controls="tab_fields">
            <a role="tab" data-toggle="tab" href="#tab_fields">Fields</a>
        </li>

        <li role='presentation' aria-controls="tab_advanced">
            <a role="tab" data-toggle="tab" href="#tab_advanced">Advanced</a>
        </li>
    </ul>

    <form method="POST" id="block-type-form"
          action="{{ route('soda.block-types.' . ($blockType->id ? 'update' : 'store'), $blockType->id) }}"
          enctype="multipart/form-data">
        <div class="tab-content">
            <div class="tab-pane" id="tab_settings" role="tabpanel">
                <div class="content-block">

                    {!! csrf_field() !!}
                    {!! method_field($blockType->id ? 'PUT' : 'POST') !!}

                    {!! SodaForm::text([
                        'name'        => 'Block Type Name',
                        'field_name'  => 'name',
                    ])->setModel($blockType) !!}

                    {!! SodaForm::textarea([
                        'name'        => 'Block Type Description',
                        'field_name'  => 'description',
                    ])->setModel($blockType) !!}

                    {!! SodaForm::text([
                        'name'        => 'Identifier',
                        'field_name'  => 'identifier',
                    ])->setModel($blockType) !!}
                </div>
            </div>
            <div class="tab-pane" id="tab_fields" role="tabpanel">
                <div class="content-block">
                    @if($blockType->id)
                        @if(count($blockType->fields))
                            <table class="table">
                                <thead>
                                <tr>
                                    <th width="20"></th>
                                    <th>Field</th>
                                    <th width="120">Show in table</th>
                                    <th width="175">Options</th>
                                </tr>
                                </thead>
                                <tbody class="sortable" data-entityname="block-types.fields">
                                @foreach($blockType->fields as $field)
                                    <tr data-itemId="{{ $field->id }}" data-parentId="{{ $blockType->id }}">
                                        <td class="sortable-handle"><img src="/soda/cms/img/drag-dots.gif"/></td>
                                        <td>{{ $field->name }}</td>
                                        <td>{{ $field->pivot->show_in_table ? 'Yes' : 'No' }}</td>
                                        <td>
                                            <a href='{{ route('soda.fields.edit', $field->id) }}'
                                               class='btn btn-warning' target="_blank"><i class='fa fa-pencil'></i>
                                                <span>Edit</span></a>
                                            <button type="button" class="btn btn-danger"
                                                    data-detach="{{ route('soda.block-types.fields.detach', [$blockType->id, $field->id]) }}">
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
                        Please save the block type before managing fields.
                    @endif
                </div>
            </div>


            <div class="tab-pane" id="tab_advanced" role="tabpanel">
                <div class="content-block">
                    <div class="row fieldset-group">
                        <div class="col-sm-6 col-xs-12">
                            {!! SodaForm::dropdown([
                                'name'        => 'List Action',
                                'field_name'  => 'list_action_type',
                                'field_params' => ['options' => Soda\Cms\Foundation\Constants::PAGE_ACTION_TYPES],
                                'description'  => 'Specifies the interface supplied when listing this block.',
                            ])->setModel($blockType)->setLayout(soda_cms_view_path('partials.inputs.layouts.inline-group')) !!}
                        </div>
                        <div class="col-sm-6 col-xs-12">
                            {!! SodaForm::text([
                                'name'        => null,
                                'field_name'  => 'list_action',
                            ])->setModel($blockType)->setLayout(soda_cms_view_path('partials.inputs.layouts.inline-group')) !!}
                        </div>
                    </div>

                    <div class="row fieldset-group">
                        <div class="col-sm-6 col-xs-12">
                            {!! SodaForm::dropdown([
                                'name'        => 'Edit Action',
                                'field_name'  => 'edit_action_type',
                                'field_params' => ['options' => Soda\Cms\Foundation\Constants::PAGE_ACTION_TYPES],
                                'description'  => 'Specifies the interface supplied when editing this block.',

                            ])->setModel($blockType)->setLayout(soda_cms_view_path('partials.inputs.layouts.inline-group')) !!}
                        </div>
                        <div class="col-sm-6 col-xs-12">
                            {!! SodaForm::text([
                                'name'        => null,
                                'field_name'  => 'edit_action',
                            ])->setModel($blockType)->setLayout(soda_cms_view_path('partials.inputs.layouts.inline-group')) !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <div class="content-bottom">
        @include(soda_cms_view_path('partials.buttons.save'), ['submits' => '#block-type-form'])
    </div>
@endsection



@section('modals')
    @parent

    @if($blockType->id)
        <div class="modal fade" id="newFieldModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                    aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">Select a field to add...</h4>
                    </div>
                    <form method="POST" action="{{ route('soda.block-types.fields.attach', $blockType->id) }}">
                        {!! csrf_field() !!}
                        <div class="modal-body">
                            {!! SodaForm::dropdown([
                                "name"         => "Fields",
                                "field_name"   => 'fieldable_id',
                                "field_params" => ['options' => $fields->pluck('name', 'id')]
                            ])->setLayout(soda_cms_view_path('partials.inputs.layouts.stacked')) !!}

                            {!! SodaForm::toggle([
                                "name"        => "Show in table?",
                                "field_name"  => "show_in_table",
                                "value"       => 1,
                                "description" => "Determines whether field value is shown in table view when listing blocks of this type"
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
    <script>
        $(function () {
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
