@extends(soda_cms_view_path('wrappers.'.$render))

@section('wrapper-content')
	<p>{{ $block->type->description}}</p>
	<hr />
	<table class="table">
		<thead>
		<tr>
			@foreach($block->type->fields as $field)
				@if($field->show_in_table)
				<th>{{ $field->name }}</th>
				@endif
			@endforeach
			<th>Options</th>
		</tr>
		</thead>
		<tbody>
		@foreach($models as $model)
			<tr>
				@foreach($block->type->fields as $field)
					{{--TODO: additional logic in here for different field types--}}
					@if($field->show_in_table)
						@if($field->field_type == 'fancy_upload')
							@switch( @pathinfo($model->{$field->field_name})['extension'] )
							@case( 'jpg' )
							@case( 'png' )
							@case( 'gif' )
							@case( 'bmp' )
							@case( 'tiff' )
							<td><img src="{{ $model->{$field->field_name} }}" alt="" width="120"/></td>
							@break
							@case( 'mp3' )
							@case( 'wav' )
							@case( 'm4a' )
							<td><audio src="{{ $model->{$field->field_name} }}" alt="" width="120"/></td>
							@break
							@default
							<td>{{ truncate_words(strip_tags($model->{$field->field_name}), 10) }}</td>
							@break
							@endswitch
						@elseif($field->field_type == 'datetime')
							<td>{{ $model->{$field->field_name} }}</td>
						@else
							<td>{{ truncate_words(strip_tags($model->{$field->field_name}), 10) }}</td>
						@endif
					@endif
				@endforeach
				<td>
					<a href="{{route('soda.page.block.view', ['page_id' => $page->id, 'id'=>$model->id, 'type'=>$block->identifier])}}" class="btn btn-success btn-sm"><span class="fa fa-pencil"></span></a>
					@if($block->pivot->can_delete)
					<a href="{{route('soda.page.block.delete', ['page_id' => $page->id, 'id'=>$model->id, 'type'=>$block->identifier])}}" class="btn btn-danger btn-sm"><span class="fa fa-remove"></span></a>
					@endif
				</td>
			</tr>
		@endforeach
		</tbody>
	</table>

	{!! $models->render() !!}
	@if($block->pivot->can_create)
	<a href="{{route('soda.page.block.view',['page_id' => $page->id, 'type' => $block->identifier])}}" class="btn btn-primary">Create</a>
	@endif
@overwrite
