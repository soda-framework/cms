@extends(config('soda.hint_path').'::layouts.inner')

@section('header')

    <title>View Page</title>
    <!-- JavaScripts -->
    <script src="/soda/soda/js/content.js"></script>
    <!-- Styles -->
    <link href="/soda/soda/css/content.css" rel="stylesheet">

@endsection

@section('content')

    <p>{{$model->description}}</p>

    @include(config('soda.hint_path').'::partials.heading',['icon'=>'fa fa-file-o', 'title'=> $model->name ? $model->name : 'New ' . $model->type->name . " Page"])

    <ul class="nav nav-tabs" role="tablist">
        @if(Soda\Models\Page::hasFieldsOrBlocks($model))

            <li role='presentation' class="active" aria-controls="{{ $model->type->name }}">
                <a role="tab" data-toggle="tab" href="#normalview">{{ $model->type->name }}</a>
            </li>
            <li role='presentation' aria-controls="Page View">
        @else
            <li role='presentation' class="active" aria-controls="Page View">
                @endif
                <a role="tab" data-toggle="tab" href="#pageview">Page</a>
            </li>
            <li role='presentation' aria-controls="Live View">
                <a role="tab" data-toggle="tab" href="#liveview">Live View</a>
            </li>
            <li role='presentation' aria-controls="Advanced View">
                <a role="tab" data-toggle="tab" href="#advancedview">Advanced</a>
            </li>
    </ul>

    @if($model->id)
        <form class="" method="POST" action="{{route('soda.'.$hint.'.edit',['id'=>@$model->id])}}">{{-- << TODO --}}
            @else
                <form class="" method="POST" action="{{route('soda.'.$hint.'.create',['id'=>@$model->parent_id])}}">{{-- << TODO --}}
                    @endif
                    {!! csrf_field() !!}
                    <input type="hidden" name="page_type_id" value="{{ $model->type->id }}" />
                    <div class="tab-content">
                        @if(Soda\Models\Page::hasFieldsOrBlocks($model))
                            <div class="tab-pane active" id="normalview" role="tabpanel">
                                @if(@$model->type->fields)
                                    @foreach($model->type->fields as $field)
                                        {!! Soda::field($field)->setModel(@$page_table)->setPrefix('settings') !!}
                                    @endforeach
                                @endif
                                @foreach($model->blocks as $block)
                                    {{--loads a block into place.. --}}
                                    @include($block->type->edit_action_type,['unique'=>uniqid(), 'render'=>'card', 'name'=>$block->type->name, 'fields'=>$block->type->fields, 'type'=>$block->type, 'models'=>Soda::dynamicModel('soda_'.$block->type->identifier, $block->type->fields->lists('field_name')->toArray())->paginate()])
                                @endforeach
                            </div>
                            <div class="tab-pane" id="pageview" role="tabpanel">
                                @else
                                    <div class="tab-pane active" id="pageview" role="tabpanel">
                                        @endif
                                        <p>Customise page details</p>
                                        {!! Soda::field([
                                            "name"        => "Name",
                                            "description" => "The name of this page",
                                            'field_type'  => 'text',
                                            "field_name"  => 'name',
                                        ])->setModel($model) !!}

                                        {!! Soda::field([
                                            'name'        => 'Slug',
                                            'description' => 'The url of this page',
                                            'field_type'  => 'text',
                                            'field_name'  => 'slug',
                                        ])->setModel($model) !!}

                                        {!! Soda::field([
                                            'name'        => 'Description',
                                            'description' => 'The description of this page',
                                            'field_type'  => 'text',
                                            'field_name'  => 'description',
                                        ])->setModel($model) !!}

                                        {!! Soda::field([
                                            'name'         => 'Status',
                                            'description'  => 'The status of this page',
                                            'field_type'   => 'dropdown',
                                            'field_name'   => 'status',
                                            'field_value'  => $model->status_id,
                                            'field_params' => ['options' => Soda\Models\Status::lists('name','id')],
                                        ])->setModel($model) !!}
                                    </div>

                                    <div class="tab-pane" id="liveview" role="tabpanel">
                                        @if($model->slug)
                                            <p>Use this tab to customise information on the page in a live view</p>
                                            <p>{{ $model->slug }}</p>
                                            <iframe width="100%" height=400 src="{{ $model->slug }}?soda_edit=true"></iframe>
                                        @else
                                            <p>You must set a slug to enabled this feature.</p>
                                        @endif
                                    </div>

                                    <div class="tab-pane" id="advancedview" role="tabpanel">
                                        <p>Advanced page details</p>

                                        {!! Soda::field([
                                            'name'        => 'Package Name',
                                            'field_type'  => 'text',
                                            'field_name'  => 'package',
                                        ])->setModel($model) !!}

                                        {!! Soda::field([
                                            'name'        => 'Action',
                                            'field_type'  => 'text',
                                            'field_name'  => 'action',
                                        ])->setModel($model) !!}

                                        {!! Soda::field([
                                            'name'        => 'Action Type',
                                            'field_type'  => 'text',
                                            'field_name'  => 'action_type',
                                        ])->setModel($model) !!}

                                        {!! Soda::field([
                                            'name'        => 'Edit Action',
                                            'field_type'  => 'text',
                                            'field_name'  => 'edit_action',
                                        ])->setModel($model) !!}

                                        {!! Soda::field([
                                            'name'        => 'Edit Action Type',
                                            'field_type'  => 'text',
                                            'field_name'  => 'edit_action_type',
                                        ])->setModel($model) !!}

                                        {!! Soda::field([
                                            'name'        => 'Description',
                                            'field_type'  => 'textarea',
                                            'field_name'  => 'description',
                                        ])->setModel($model) !!}
                                    </div>
                            </div>
                            <input class="btn btn-success" type="submit" value="save"/>
                </form>
@endsection
