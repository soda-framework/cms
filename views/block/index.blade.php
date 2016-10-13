@extends(soda_cms_view_path('layouts.inner'))

@section('breadcrumb')
	<ol class="breadcrumb">
		<li><a href="{{ route('soda.home') }}">Home</a></li>
		<li class="active">Blocks</li>
	</ol>
@stop

@section('head.title')
	<title>Soda CMS | Blocks</title>
@endsection

@include(soda_cms_view_path('partials.heading'), [
    'icon'        => 'fa fa-pencil',
    'title'       => 'Blocks',
    'description' => 'Blocks are added onto pages',
])

@section('content')
	{!! $filter !!}
	{!! $grid !!}
	<a class='btn btn-primary' data-toggle="modal" data-target="#block_type_modal">
	    <i class="fa fa-plus"></i>
	    <span>Create</span>
    </a>

	<div class="modal fade" id="block_type_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
								aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="myModalLabel">Select a block type..</h4>
				</div>
				<form method="GET" action="{{route('soda.'.$hint.'.create')}}">
					<div class="modal-body">
						<fieldset class="form-group field_page_type page_type dropdown-field">
							<label for="field_block_type">Block Type</label>

							<select name="block_type_id" class="form-control" id="block_type_id">
								@foreach($block_types as $block_type)
									<option value="{{$block_type->id}}">{{$block_type->name}}</option>
								@endforeach
							</select>
						</fieldset>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
						<button  class="btn btn-primary">Create Block</button>
					</div>
				</form>
			</div>
		</div>
	</div>
@endsection
