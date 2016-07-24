@extends(config('soda.hint_path').'::layouts.inner')

@section('header')

	<title>Pages</title>
	{{--note: non of these have anything in them anymore--}}
			<!-- JavaScripts -->
	<script src="/sodacms/sodacms/js/content.js"></script>
	<!-- Styles -->
	<link href="/sodacms/sodacms/css/content.css" rel="stylesheet">

@endsection

@section('content')
	@include(config('soda.hint_path').'::partials.heading',['icon'=>'fa fa-file-o', 'title'=>'Pages'])
	{!! $tree !!}

	<a href="#" class="btn btn-primary btn-lg" data-toggle="modal" data-ts="btn" data-target="#page_type_modal">
		Create Page
	</a>

	<div class="modal fade" id="page_type_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		<div class="modal-dialog" role="document">
			<form class="modal-content" method="GET" action="{{route($routeHint.'create')}}">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
								aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="myModalLabel">Select a page type..</h4>
				</div>
				<div class="modal-body">
					<fieldset class="form-group field_page_type page_type  dropdown-field">
						<label for="field_page_type">Page Type</label>

						<select name="page_type_id" class="form-control" id="page_type_id">
							@foreach($page_types as $page_type)
								<option value="{{$page_type->id}}">{{$page_type->name}}</option>
							@endforeach
						</select>
					</fieldset>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					<button  class="btn btn-primary" >Create Page</button>
				</div>
			</form>
		</div>
	</div>

@endsection
