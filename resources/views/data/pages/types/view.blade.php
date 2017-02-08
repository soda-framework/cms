@extends(soda_cms_view_path('layouts.inner'))

@section('breadcrumb')
    <ol class="breadcrumb">
        <li><a href="{{ route('soda.home') }}">Home</a></li>
        <li><a href="{{ route('soda.page-types.index') }}">Page Types</a></li>
        <li class="active">{{ $pageType->name ? $pageType->name : 'New Page Type' }}</li>
    </ol>
@stop

@section('head.title')
    <title>Soda CMS | Page Types</title>
@endsection

@section('content-heading-button')
    @include(soda_cms_view_path('partials.buttons.save'), ['submits' => '#page-type-form'])
@stop

@include(soda_cms_view_path('partials.heading'), [
    'icon'        => 'fa fa-edit',
    'title'       => $pageType->name ? 'Page Type: ' . $pageType->name : 'New Page Type',
])

@section('content')
    <ul class="nav nav-tabs" role="tablist">
        <li role='presentation' aria-controls="tab_settings">
            <a role="tab" data-toggle="tab" href="#tab_settings">Settings</a>
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

                    {!! SodaForm::text([
                        "name"        => 'Page Type Name',
                        "field_name"  => 'name',
                    ])->setModel($pageType) !!}

                    {!! SodaForm::textarea([
                        "name"        => "Page Type Description",
                        "field_name"  => 'description',
                    ])->setModel($pageType) !!}

                    {!! SodaForm::text([
                        "name"        => "Identifier",
                        "field_name"  => 'identifier',
                    ])->setModel($pageType) !!}

                    {!! SodaForm::toggle([
                        'name'         => 'Can Create',
                        'field_name'   => 'can_create',
                        'value'        => 1,
                        'field_params' => ['checked-value' => 1, 'unchecked-value' => 0],
                        'description'  => 'If enabled, pages of this type can be created from the CMS interface'
                    ])->setModel($pageType) !!}
                </div>
            </div>

            <div class="tab-pane" id="tab_subpages" role="tabpanel">
                <div class="content-block">
                    {!! $allowedChildrenFormItem = SodaForm::toggle([
                        'name'         => 'Allowed Subpages',
                        'field_name'   => 'allowed_children',
                        'value'        => 1,
                        'field_params' => ['checked-value' => 1, 'unchecked-value' => 0],
                        'description'  => 'If enabled, pages of this type can have child pages'
                    ])->setModel($pageType) !!}

                    <?php
                    $subpageIds = $pageType->subpage_types->pluck('id')->toArray();
                    ?>

                    <div class="restricted_page_types" style="display:none">
                        {!! $restrictePageTypesFormItem = SodaForm::toggle([
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
                                            {!! SodaForm::toggle([
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

                        @foreach($pageType->block_types as $blockType)
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
                    <a class="btn btn-lg btn-success" href="#" data-add-block>Add Block</a>
                    @else
                        Please save the page type before managing blocks.
                    @endif
                </div>
            </div>

            <div class="tab-pane" id="tab_advanced" role="tabpanel">
                <div class="content-block">
                    <div class="row fieldset-group">
                        <div class="col-sm-6 col-xs-12">
                            {!! SodaForm::dropdown([
                                'name'        => 'View Action',
                                'field_name'  => 'view_action_type',
                                'field_params' => ['options' => Soda\Cms\Foundation\Constants::PAGE_ACTION_TYPES],
                                'description'  => 'Specifies the interface supplied when viewing pages of this type.',
                            ])->setModel($pageType)->setLayout(soda_cms_view_path('partials.inputs.layouts.inline-group')) !!}
                        </div>
                        <div class="col-sm-6 col-xs-12">
                            {!! SodaForm::text([
                                'name'        => null,
                                'field_name'  => 'view_action',
                            ])->setModel($pageType)->setLayout(soda_cms_view_path('partials.inputs.layouts.inline-group')) !!}
                        </div>
                    </div>

                    <div class="row fieldset-group">
                        <div class="col-sm-6 col-xs-12">
                            {!! SodaForm::dropdown([
                                'name'        => 'Edit Action',
                                'field_name'  => 'edit_action_type',
                                'field_params' => ['options' => Soda\Cms\Foundation\Constants::PAGE_ACTION_TYPES],
                                'description'  => 'Specifies the interface supplied when editing pages of this type.',

                            ])->setModel($pageType)->setLayout(soda_cms_view_path('partials.inputs.layouts.inline-group')) !!}
                        </div>
                        <div class="col-sm-6 col-xs-12">
                            {!! SodaForm::text([
                                'name'        => null,
                                'field_name'  => 'edit_action',
                            ])->setModel($pageType)->setLayout(soda_cms_view_path('partials.inputs.layouts.inline-group')) !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

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
                        {!! SodaForm::dropdown([
                            "name"         => "Blocks",
                            "field_name"   => 'block_id',
                            "field_params" => ['options' => $blockTypes->pluck('name', 'id')]
                        ])->setLayout(soda_cms_view_path('partials.inputs.layouts.stacked')) !!}

                        {!! SodaForm::text([
                            "name"        => "Max Blocks",
                            "field_name"  => "max_blocks",
                            "description" => "Defines the maximum number of rows this block can hold. Default: unlimited"
                        ])->setLayout(soda_cms_view_path('partials.inputs.layouts.stacked')) !!}

                        {!! SodaForm::text([
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
    @endif

    <div class="content-bottom">
        @include(soda_cms_view_path('partials.buttons.save'), ['submits' => '#page-type-form'])
    </div>
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
        });
    </script>
@stop
