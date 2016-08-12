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

	@include(config('soda.hint_path').'::partials.heading',['icon'=>'fa fa-file-o', 'title'=>$model->name?$model->name:'New '. $model->type->name." Page"])

	<ul class="nav nav-tabs" role="tablist">
		@if(Soda\Models\Page::hasFieldsOrBlocks($model))
			<li role='presentation' class="active" aria-controls="{{$model->name}}">
				<a role="tab" data-toggle="tab" href="#normalview">{{$model->name}}</a>
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
		<div class="tab-content">
			@if(Soda\Models\Page::hasFieldsOrBlocks($model))
				<div class="tab-pane active" id="normalview" role="tabpanel">
					@if(@$model->type->fields)
						@foreach($model->type->fields as $field)
								@include("soda::inputs.".$field->field_type,['field_name'=>'settings['.$field->field_name.']', 'field_value'=>(@$model_table?@$model_table->{$field->field_name}:$field->value), 'field_label'=>$field->name])
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
				@include("soda::inputs.text",['field_name'=>'name', 'field_value'=>$model->name, 'field_label'=>'Name', 'field_info'=>'The name of this page'])
				@include("soda::inputs.text",['field_name'=>'slug', 'field_value'=>$model->slug, 'field_label'=>'Slug', 'field_info'=>'The url of this page'])
				@include("soda::inputs.text",['field_name'=>'description', 'field_value'=>$model->description, 'field_label'=>'Description', 'field_info'=>'The description of this page'])
				@include("soda::inputs.dropdown",['field_name'=>'status', 'field_value'=>$model->status_id, 'field_options'=>Soda\Models\Status::lists('name','id'), 'field_label'=>'Status', 'field_info'=>'The status of this page'])
			</div>
			<div class="tab-pane" id="liveview" role="tabpanel">
				<p>Use this tab to customise information on the page in a live view</p>
				<p>{{$model->slug}}</p>
				<iframe width="100%" height=400 src="{{$model->slug}}?soda_edit=true"></iframe>
			</div>
			<div class="tab-pane" id="advancedview" role="tabpanel">
				<p>Advanced page details</p>
				@include("soda::inputs.text",['field_name'=>'package', 'field_value'=>$model->package, 'field_label'=>'Package Name'])
				@include("soda::inputs.text",['field_name'=>'action', 'field_value'=>$model->action, 'field_label'=>'Action'])
				@include("soda::inputs.text",['field_name'=>'action_type', 'field_value'=>$model->action_type, 'field_label'=>'Action Type'])
				@include("soda::inputs.text",['field_name'=>'edit_action', 'field_value'=>$model->edit_action, 'field_label'=>'Edit Action'])
				@include("soda::inputs.text",['field_name'=>'edit_action_type', 'field_value'=>$model->edit_action_type, 'field_label'=>'Edit Action Type'])
				@include("soda::inputs.textarea",['field_name'=>'description', 'field_value'=>$model->description, 'field_label'=>'Description'])
			</div>
		</div>
		<input class="btn btn-success" type="submit" value="save"/>
	</form>
@endsection