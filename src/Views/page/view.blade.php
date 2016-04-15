@extends(config('soda.hint_path').'::layouts.inner')

@section('header')

	<title>View Page</title>
	<!-- JavaScripts -->
	<script src="/soda/soda/js/content.js"></script>
	<!-- Styles -->
	<link href="/soda/soda/css/content.css" rel="stylesheet">

@endsection

@section('content')

	<h1>{{$page->name}}</h1>
	<p>{{$page->description}}</p>
	<ul class="nav nav-tabs" >
		<li class="nav-item" aria-controls="Normal View">
			<a class="nav-link active" role="tab" data-toggle="tab" href="#normalview">Normal</a>
		</li>
		<li class="nav-item" aria-controls="Block View">
			<a class="nav-link" role="tab" data-toggle="tab" href="#blockview">Blocks</a>
		</li>
		<li class="nav-item" aria-controls="Live View">
			<a class="nav-link" role="tab" data-toggle="tab" href="#liveview">Live View</a>
		</li>
		<li class="nav-item" aria-controls="Advanced View">
			<a class="nav-link" role="tab" data-toggle="tab" href="#advancedview">Advanced</a>
		</li>
	</ul>
	@if($page->id)
		<form class="" method="POST" action="{{route($routeHint.'edit',['id'=>@$page->id])}}">{{-- << TODO --}}
	@else
		<form class="" method="POST" action="{{route($routeHint.'create',['id'=>@$page->parent_id])}}">{{-- << TODO --}}
	@endif
		{!! csrf_field() !!}
		<div class="tab-content">
			<div class="tab-pane active" id="normalview" role="tabpanel">
				<p>Customise page details</p>
				@include("soda::inputs.text",['field_name'=>'name', 'field_value'=>$page->name, 'field_label'=>'Name', 'field_info'=>'The name of this page'])
				@include("soda::inputs.text",['field_name'=>'slug', 'field_value'=>$page->slug, 'field_label'=>'Slug', 'field_info'=>'The url of this page'])
				@foreach($page->type->fields as $field)
						@include("soda::inputs.".$field->field_type,['field_name'=>$field->field_name, 'field_value'=>$page_table->{$field->field_name}, 'field_label'=>$field->name])
				@endforeach
				@include("soda::inputs.dropdown",['field_name'=>'status', 'field_value'=>$page->status_id, 'field_options'=>Soda\Models\Status::lists('name','id'), 'field_label'=>'Status', 'field_info'=>'The status of this page'])
			</div>
			<div class="tab-pane" id="blockview" role="tabpanel">
				<p>Customise page block details</p>
				@foreach($page->blocks as $block)
					{{--loads a block into place.. --}}
					@include($block->type->edit_action_type,['render'=>'card', 'name'=>$block->type->name, 'fields'=>$block->type->fields, 'type'=>$block->type, 'models'=>Soda::dynamicModel('soda_'.$block->type->identifier, $block->type->fields->lists('field_name')->toArray())->paginate()])
				@endforeach
			</div>
			<div class="tab-pane" id="liveview" role="tabpanel">
				<p>Use this tab to customise information on the page in a live view</p>
				<p>{{$page->slug}}</p>
				<iframe width="100%" height=400 src="{{$page->slug}}?soda_edit=true"></iframe>
			</div>
			<div class="tab-pane" id="advancedview" role="tabpanel">
				<p>Advanced page details</p>
				@include("soda::inputs.text",['field_name'=>'action', 'field_value'=>$page->action, 'field_label'=>'Action'])
				@include("soda::inputs.text",['field_name'=>'action_type', 'field_value'=>$page->action_type, 'field_label'=>'Action Type'])
				@include("soda::inputs.text",['field_name'=>'edit_action', 'field_value'=>$page->edit_action, 'field_label'=>'Edit Action'])
				@include("soda::inputs.text",['field_name'=>'edit_action_type', 'field_value'=>$page->edit_action_type, 'field_label'=>'Edit Action Type'])
				@include("soda::inputs.textarea",['field_name'=>'description', 'field_value'=>$page->description, 'field_label'=>'Description'])
			</div>
		</div>
		<input class="btn btn-success" type="submit" value="save"/>
	</form>
@endsection