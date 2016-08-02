@if($model->parent_id)
	@include(config('soda.hint_path').'::partials.heading',['icon'=>'fa fa-compass', 'title'=>$model->id?'Navigation Item: '.$model->id:'New Navigation Item'])
@else
	@include(config('soda.hint_path').'::partials.heading',['icon'=>'fa fa-compass', 'title'=>$model->id?'Navigation Menu: '.$model->id:'New Navigation Menu'])
@endif
<form method="POST" action='{{route('soda.'.$hint.'.edit',['id'=>@$model->id])}}' class="form--wrapper" enctype="multipart/form-data">
	<input type="hidden" name="_token" value="{{ csrf_token() }}" />

	<input type="hidden" name="parent_id" value="{{$model->parent_id}}" />
	@include('soda::inputs.text',['field_name'=>'name', 'field_value'=>$model->name , 'field_label'=>'Navigation Name'])
	@include('soda::inputs.text',['field_name'=>'url', 'field_value'=>$model->url , 'field_label'=>'Url'])
	@include('soda::inputs.text',['field_name'=>'class', 'field_value'=>$model->class , 'field_label'=>'Navigation Class'])
	@include('soda::inputs.text',['field_name'=>'text', 'field_value'=>$model->text , 'field_label'=>'Navigation Text'])
	@include('soda::inputs.textarea',['field_name'=>'description', 'field_value'=>$model->description , 'field_label'=>'Navigation Description'])
	@include('soda::inputs.code',['field_name'=>'html', 'field_value'=>$model->html , 'field_label'=>'Navigation HTML'])
	@if($model->name)
		<button class="btn btn-primary"><span class="fa fa-pencil"></span> Update</button>
	@else
		<button class="btn btn-primary"><span class="fa fa-plus"></span> Create</button>
	@endif
</form>