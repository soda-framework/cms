@extends(soda_cms_view_path('layouts.inner'))

@section('header')
    <title>View Page</title>
@endsection

@section('content')

    @include(soda_cms_view_path('partials.heading'), [
        'icon'  => 'fa fa-file-o',
        'title' => $model->name ? $model->name : 'New ' . ($model->type ? $model->type->name . " Page" : "Page"),
    ])

    <p class="text-muted">{{ $model->description }}</p><br />

    <ul class="nav nav-tabs" role="tablist">
        <li role='presentation' class="active" aria-controls="Page View">
            <a role="tab" data-toggle="tab" href="#tab_page">Page</a>
        </li>
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
        <li role='presentation' aria-controls="Live View">
            <a role="tab" data-toggle="tab" href="#tab_live">Live View</a>
        </li>
        <li role='presentation' aria-controls="Advanced View">
            <a role="tab" data-toggle="tab" href="#tab_advanced">Advanced</a>
        </li>
    </ul>

    <form method="POST" action="{{ route('soda.' . $hint . ($model->id ? '.edit' : '.create'), ['id' => $model->id]) }}">{{-- << TODO --}}
        {!! csrf_field() !!}
        @if($model->type)
        <input type="hidden" name="page_type_id" value="{{ $model->type->id }}" />
        @endif
        <input type="hidden" name="parent_id" value="{{ $model->parent_id }}" />
        <div class="tab-content">
            <div class="tab-pane active" id="tab_page" role="tabpanel">
                <p>Customise page details</p>
                {!! SodaForm::text([
                    "name"        => "Name",
                    "description" => "The name of this page",
                    "field_name"  => 'name',
                ])->setModel($model) !!}

                {!! SodaForm::text([
                    'name'        => 'Slug',
                    'description' => 'The url of this page',
                    'field_name'  => 'slug',
                ])->setModel($model) !!}

                {!! SodaForm::dropdown([
                    'name'         => 'Status',
                    'description'  => 'The status of this page',
                    'field_name'   => 'status',
                    'value'        => Soda\Cms\Components\Status::LIVE,
                    'field_params' => ['options' => Soda\Cms\Components\Status::all()],
                ])->setModel($model) !!}

                {!! SodaForm::textarea([
                    'name'        => 'Description',
                    'description' => 'The description of this page',
                    'field_name'  => 'description',
                ])->setModel($model) !!}

                <input class="btn btn-success" type="submit" value="Save" />
            </div>

            @if($model->type)
                <div class="tab-pane" id="tab_page_type_{{ $model->type->id }}" role="tabpanel">
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
                            'models' => $model->dynamicBlock($block)->paginate(null, ['*'], $block->identifier .'-page')
                        ])
                    </div>
                @endif
                {{--loads a block into place.. --}}
            @endforeach

            <div class="tab-pane" id="tab_live" role="tabpanel">
                @if($model->slug)
                    <p>Use this tab to customise information on the page in a live view</p>
                    <p>{{ $model->slug }}</p>
                    <iframe width="100%" height=400 src="{{ $model->slug }}?soda_edit=true"></iframe>
                @else
                    <p>You must set a slug to enabled this feature.</p>
                @endif
            </div>

            <div class="tab-pane" id="tab_advanced" role="tabpanel">
                <p>Advanced page details</p>

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
        </div>
    </form>
@stop
