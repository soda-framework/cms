@include('soda::inputs.text',['field_name'=>'name', 'field_value'=>$model->name , 'field_label'=>'Navigation Name'])
@include('soda::inputs.textarea',['field_name'=>'description', 'field_value'=>$model->description , 'field_label'=>'Navigation Description'])
@if($model->name)
	<button class="btn btn-primary"><span class="fa fa-pencil"></span> Update</button>
@else
	<button class="btn btn-primary"><span class="fa fa-plus"></span> Create</button>
@endif