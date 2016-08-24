@extends(config('soda.hint_path').'::layouts.inner')

@section('header')

	<title>Fields</title>
	{{--note: non of these have anything in them anymore--}}
			<!-- JavaScripts -->
	<script src="/sodacms/sodacms/js/content.js"></script>
	<!-- Styles -->
	<link href="/sodacms/sodacms/css/content.css" rel="stylesheet">

@endsection

@section('content')
	@include(config('soda.hint_path').'::partials.heading',['icon'=>'fa fa-pencil', 'title'=>'Block'])
	<p>
		Blocks are added onto pages
	</p>
	{!! $filter !!}
	{!! $grid !!}
	<a class='btn btn-primary' data-toggle="modal" data-target="#block_type_modal"><span class="fa fa-plus"></span> Create</a>

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
