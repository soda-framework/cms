<?php
//create new treeable item(s)
php artisan closuretable:make --entity=navigation --namespace=Soda\Models --models-path=soda/soda/src/Models


//Soda::getBlock('banners'))
//This is the page that renders the actual homepage (front end).

//$model = Soda::dynamicModel('soda_banners')->paginate();
$block = \Soda\Models\Block::find(4);
$block_type = $block->type()->first();


//$type = \Soda\Models\Type::find(2);
//$flds = $type->fields()->get();


////////////////
//create types table based off given type
////////////////
//dd($type->addType($flds));

///////////////
//add field to given type..
//////////////
//$data = array(
//		array('name'=>'arsebandit', 'field_name'=>'blargh'),
//		array('name'=>'Coder2', 'field_name'=>'hgfdhgf'),
//);
////we can probs use ->fill or something for this so it's not so shit.
//foreach($data as $d){
//	$field = new Soda\Models\Field();
//	$field->fill($d);
//	$field->save();
//	$fields[] = $field;
//}
//
//dd($type->addFieldsToType($fields));

//$models = Soda::dynamicModel('soda_banners')->paginate();
//@include($block_type->edit_action_type,['title'=>$block_type->name, 'model' ,'models'=>$models, 'type'=>strtolower($block_type->name)])
?>

{{--
<table class="table">
	<thead>
	<tr>
		@foreach($flds as $field)
			<th>{{$field->name}}</th>
		@endforeach
			<th>update</th>
	</tr>
	</thead>
	<tbody>
	@foreach($model as $mod)
		<tr>
			@foreach($flds as $field)
				TODO: additional logic in here for different field types
				<td>{{ $mod->{$field->field_name} }}</td>
			@endforeach
			<td>
				<a href="#" class="btn btn-primary">edit</a>
				<a href="#" class="btn btn-primary">delete</a>
			</td>
		</tr>
	@endforeach
	</tbody>
</table>

<a href="#" class="btn btn-primary">create {{$type->name}}</a>

--}}